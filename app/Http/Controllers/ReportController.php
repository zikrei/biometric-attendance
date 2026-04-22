<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\User; 
use Barryvdh\DomPDF\Facade\Pdf; 
use App\Models\Department; 

class ReportController extends Controller
{
    // Helper method to get the correct dropdown data securely based on role
    private function getDropdownData()
    {
        $currentUser = auth()->user();
        
        if ($currentUser->role?->name === 'HOD') {
            $departments = Department::where('id', $currentUser->department_id)->get();
            
            // Fetch EVERYONE in the HOD's department (Including the HOD themselves!)
            $users = User::where('department_id', $currentUser->department_id)
                         ->orderBy('name')->get(['id', 'name', 'department_id']);
        } else {
            $departments = Department::all();
            $users = User::orderBy('name')->get(['id', 'name', 'department_id']); 
        }
        
        return [$departments, $users];
    }

    // Show the report generation filter page
    public function index()
    {
        [$departments, $users] = $this->getDropdownData();
        return view('reports.index', compact('departments', 'users'));
    }

    // Generate the preview report
    public function generate(Request $request)
    {
        $monthInput = $request->input('month', now()->format('Y-m'));
        $departmentId = $request->input('department_id');
        $userId = $request->input('user_id');
        $currentUser = auth()->user();

        $query = Attendance::with(['user.department', 'justification']);

        // 1. Role Security Scoping
        if ($currentUser->role?->name === 'HOD') {
            // Force the department ID to the HOD's department
            $departmentId = $currentUser->department_id; 
            
            $query->whereHas('user', function($q) use ($departmentId) {
                // Allow them to see all records for their department
                $q->where('department_id', $departmentId);
            });
        } elseif ($currentUser->role?->name === 'Integrity') {
            $query->whereHas('justification', function($q) {
                $q->where('status', 'approved');
            });
        }

        // 2. Month Filter
        if ($monthInput) {
            $parts = explode('-', $monthInput);
            $query->whereYear('date', $parts[0])->whereMonth('date', $parts[1]);
        }

        // 3. User / Department Filter (For Admin & Integrity)
        if ($userId) {
            $query->where('user_id', $userId);
        } elseif ($departmentId && $currentUser->role?->name !== 'HOD') {
            $query->whereHas('user', function($q) use ($departmentId) {
                $q->where('department_id', $departmentId);
            });
        }

        $attendances = $query->orderBy('date', 'desc')->get();

        $department = null;
        if ($departmentId) {
            $department = Department::find($departmentId);
        }

        [$departments, $users] = $this->getDropdownData();

        return view('reports.preview', compact('monthInput', 'attendances', 'department', 'departments', 'users'));
    }

    public function show($id)
    {
        return view('reports.show');
    }

    // Print the report (Grouped by User)
    public function print(Request $request)
    {
        $selectedMonth = $request->input('month', now()->format('Y-m'));
        $departmentId = $request->input('department_id');
        $userId = $request->input('user_id'); 
        $currentUser = auth()->user();

        $query = \App\Models\User::with(['attendances' => function($q) use ($selectedMonth) {
            $q->whereMonth('date', \Carbon\Carbon::parse($selectedMonth)->month)
              ->whereYear('date', \Carbon\Carbon::parse($selectedMonth)->year)
              ->with('justification');
        }]);

        // 2. Apply Security Scoping
        if ($currentUser->role?->name === 'HOD') {
            // Allow printing for anyone in their department
            $query->where('department_id', $currentUser->department_id);
        } elseif ($departmentId && !$userId) {
            $query->where('department_id', $departmentId);
        }

        // 3. Apply the specific User filter
        if ($userId) {
            $query->where('id', $userId);
        }

        $users = $query->orderBy('name')->get();

        return view('reports.print', compact('users', 'selectedMonth'));
    }

    // Export the report to PDF
    public function export(Request $request)
    {
        $selectedMonth = $request->input('month', now()->format('Y-m'));
        $departmentId = $request->input('department_id');
        $userId = $request->input('user_id'); 
        $currentUser = auth()->user();

        $query = \App\Models\User::with(['attendances' => function($q) use ($selectedMonth) {
            $q->whereMonth('date', \Carbon\Carbon::parse($selectedMonth)->month)
              ->whereYear('date', \Carbon\Carbon::parse($selectedMonth)->year)
              ->with('justification');
        }]);

        // 2. Apply Security Scoping
        if ($currentUser->role?->name === 'HOD') {
            // Allow exporting for anyone in their department
            $query->where('department_id', $currentUser->department_id);
        } elseif ($departmentId && !$userId) {
            $query->where('department_id', $departmentId);
        }

        // 3. Apply the specific User filter
        if ($userId) {
            $query->where('id', $userId);
        }

        $users = $query->orderBy('name')->get();

        $pdf = Pdf::loadView('reports.print', compact('users', 'selectedMonth'));
        
        return $pdf->download('Department_Attendance_Report.pdf');
    }
}