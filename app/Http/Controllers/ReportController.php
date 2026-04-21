<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\User; // <-- REQUIRED: Tells the controller where to find the users!
use Barryvdh\DomPDF\Facade\Pdf; // <-- Add this to unlock PDF features!
use App\Models\Department; // Make sure your Department model is imported!

class ReportController extends Controller
{
    // Show the report generation page
    public function index()
    {
        // Fetch all departments and users to populate the dropdowns
        $departments = Department::all();
        $users = User::all(['id', 'name', 'department_id']); 

        // FIX: Change 'admin.reports' to 'reports.index'
        return view('reports.index', compact('departments', 'users'));
    }

    // Generate the report based on user input
    public function generate(Request $request)
    {
        $monthInput = $request->input('month');
        $departmentId = $request->input('department_id');
        $userId = $request->input('user_id');

        // 1. Base query: Fetch ALL attendance records
        $query = Attendance::with(['user']);

        // 2. Role Check: If the user is Integrity, ONLY show Approved records. 
        // Admins and others will see everything.
        if (auth()->user()->role?->name === 'Integrity') {
            $query->where('status', 'Approved');
        }

        // 3. Filter by Month
        if ($monthInput) {
            $parts = explode('-', $monthInput);
            $query->whereYear('date', $parts[0])->whereMonth('date', $parts[1]);
        }

        // 4. Filter by User OR Department
        if ($userId) {
            $query->where('user_id', $userId);
        } elseif ($departmentId) {
            $query->whereHas('user', function($q) use ($departmentId) {
                $q->where('department_id', $departmentId);
            });
        }

        $attendances = $query->orderBy('date', 'desc')->get();

        return view('reports.preview', compact('monthInput', 'attendances'));
    }

    // Show the generated report
    public function show($id)
    {
        // Logic to show specific report
        return view('reports.show');
    }

    // Print the report
    public function print(Request $request)
    {
        $selectedMonth = $request->input('month', now()->format('Y-m'));
        $departmentId = $request->input('department_id'); // If filtered via a dropdown
        $currentUser = auth()->user();

        // Start querying USERS, bringing along their attendance for the specific month
        $query = \App\Models\User::with(['attendances' => function($q) use ($selectedMonth) {
            $q->whereMonth('date', \Carbon\Carbon::parse($selectedMonth)->month)
              ->whereYear('date', \Carbon\Carbon::parse($selectedMonth)->year);
        }]);

        // SECURE THE SCOPE based on role
        if ($currentUser->role?->name === 'HOD') {
            // HODs are locked to their own department's staff
            $query->where('department_id', $currentUser->department_id)
                  ->whereHas('role', function($q) {
                      $q->where('name', 'Staff');
                  });
        } elseif ($departmentId) {
            // Admin or Integrity filtering by a specific department
            $query->where('department_id', $departmentId);
        }

        $users = $query->orderBy('name')->get();

        return view('reports.print', compact('users', 'selectedMonth'));
    }

    // Export the report to PDF
    public function export(Request $request)
    {
        $monthInput = $request->input('month');
        $departmentId = $request->input('department_id');
        $userId = $request->input('user_id');

        $query = Attendance::with(['user']);

        if (auth()->user()->role?->name === 'Integrity') {
            $query->where('status', 'Approved');
        }

        if ($monthInput) {
            $parts = explode('-', $monthInput);
            $query->whereYear('date', $parts[0])->whereMonth('date', $parts[1]);
        }

        if ($userId) {
            $query->where('user_id', $userId);
        } elseif ($departmentId) {
            $query->whereHas('user', function($q) use ($departmentId) {
                $q->where('department_id', $departmentId);
            });
        }

        $attendances = $query->orderBy('date', 'desc')->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.pdf', compact('monthInput', 'attendances'));
        return $pdf->download('Attendance_Report.pdf');
    }
}