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

        // 1. Base query: Only Approved records
        $query = Attendance::with(['user'])->where('status', 'Approved');

        // 2. Filter by Month
        if ($monthInput) {
            $parts = explode('-', $monthInput);
            $query->whereYear('date', $parts[0])
                  ->whereMonth('date', $parts[1]);
        }

        // 3. Filter by User OR Department
        if ($userId) {
            // If a specific user is selected, just get them
            $query->where('user_id', $userId);
        } elseif ($departmentId) {
            // If no user is selected but a department IS, get everyone in that department
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
        $monthInput = $request->input('month');
        $departmentId = $request->input('department_id');
        $userId = $request->input('user_id');

        $query = Attendance::with(['user'])->where('status', 'Approved');

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

        return view('reports.print', compact('monthInput', 'attendances'));
    }

    // Export the report to PDF
    public function export(Request $request)
    {
        $monthInput = $request->input('month');
        $departmentId = $request->input('department_id');
        $userId = $request->input('user_id');

        $query = Attendance::with(['user'])->where('status', 'Approved');

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