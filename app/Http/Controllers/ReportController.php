<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\User; 
use Barryvdh\DomPDF\Facade\Pdf; 
use App\Models\Department; 

class ReportController extends Controller
{
    /**
     * Internal helper to retrieve context-aware dataset for filtering menus.
     */
    private function getDropdownData()
    {
        /**
         * PHASE 1: ROLE-BASED ACCESS CONTROL (RBAC) SCOPING
         * OBJECTIVE: Ensure that the available filters are restricted based on the user's administrative level.
         * PROCEDURES: 
         * - For HODs: Restricts 'departments' to their own and 'users' to departmental members (including themselves).
         * - For Admins/Integrity: Permits access to all global departments and user records.
         */
        $currentUser = auth()->user();
        
        if ($currentUser->role?->name === 'HOD') {
            $departments = Department::where('id', $currentUser->department_id)->get();
            $users = User::where('department_id', $currentUser->department_id)
                         ->orderBy('name')->get(['id', 'name', 'department_id']);
        } else {
            $departments = Department::all();
            $users = User::orderBy('name')->get(['id', 'name', 'department_id']); 
        }
        
        return [$departments, $users];
    }

    /**
     * Initialize the report configuration interface.
     */
    public function index()
    {
        /**
         * PHASE 1: INTERFACE INITIALIZATION
         * OBJECTIVE: Prepare the view with necessary lookup data for user selection.
         */
        [$departments, $users] = $this->getDropdownData();
        return view('reports.index', compact('departments', 'users'));
    }

    /**
     * Generate an on-screen preview of filtered attendance data.
     */
    public function generate(Request $request)
    {
        /**
         * PHASE 1: INPUT PARSING & QUERY INITIALIZATION
         * OBJECTIVE: Capture user parameters and initialize the primary data query.
         * DATA MAPPING: Pre-loads 'user.department' and 'justification' relationships to prevent performance bottlenecks.
         */
        $monthInput = $request->input('month', now()->format('Y-m'));
        $departmentId = $request->input('department_id');
        $userId = $request->input('user_id');
        $currentUser = auth()->user();

        $query = Attendance::with(['user.department', 'justification']);

        /**
         * PHASE 2: SECURITY SCOPING & PERMISSION ENFORCEMENT
         * OBJECTIVE: Prevent unauthorized data access between departments or roles.
         * PROCEDURES: 
         * - HOD Enforcement: Hard-codes the department filter to match the HOD's ID.
         * - Integrity Enforcement: Restricts results exclusively to records with 'approved' justifications.
         */
        if ($currentUser->role?->name === 'HOD') {
            $departmentId = $currentUser->department_id; 
            $query->whereHas('user', function($q) use ($departmentId) {
                $q->where('department_id', $departmentId);
            });
        } elseif ($currentUser->role?->name === 'Integrity') {
            $query->whereHas('justification', function($q) {
                $q->where('status', 'approved');
            });
        }

        /**
         * PHASE 3: TEMPORAL & ENTITY FILTERING
         * OBJECTIVE: Refine the dataset based on date and specific user/department selections.
         * PROCEDURES: Applies Year/Month parsing on the date string and conditionally filters by UID.
         */
        if ($monthInput) {
            $parts = explode('-', $monthInput);
            $query->whereYear('date', $parts[0])->whereMonth('date', $parts[1]);
        }

        if ($userId) {
            $query->where('user_id', $userId);
        } elseif ($departmentId && $currentUser->role?->name !== 'HOD') {
            $query->whereHas('user', function($q) use ($departmentId) {
                $q->where('department_id', $departmentId);
            });
        }

        $attendances = $query->orderBy('date', 'desc')->get();

        /**
         * PHASE 4: VIEW SYNCHRONIZATION
         * OBJECTIVE: Re-initialize dropdowns to maintain the user's context in the UI.
         */
        $department = $departmentId ? Department::find($departmentId) : null;
        [$departments, $users] = $this->getDropdownData();

        return view('reports.preview', compact('monthInput', 'attendances', 'department', 'departments', 'users'));
    }

    public function show($id)
    {
        return view('reports.show');
    }

    /**
     * Generate a printable layout grouped by user.
     */
    public function print(Request $request)
    {
        /**
         * PHASE 1: REPORT PARAMETERIZATION & SECURITY SCOPING
         * OBJECTIVE: Isolate user-grouped attendance for a specific temporal window.
         * LOGIC: 
         * - Scopes the user query to include attendances and justifications for the selected month.
         * - Enforces HOD-level department restrictions during the query build.
         */
        $selectedMonth = $request->input('month', now()->format('Y-m'));
        $departmentId = $request->input('department_id');
        $userId = $request->input('user_id'); 
        $currentUser = auth()->user();

        $query = \App\Models\User::with(['attendances' => function($q) use ($selectedMonth) {
            $q->whereMonth('date', \Carbon\Carbon::parse($selectedMonth)->month)
              ->whereYear('date', \Carbon\Carbon::parse($selectedMonth)->year)
              ->with('justification');
        }]);

        if ($currentUser->role?->name === 'HOD') {
            $query->where('department_id', $currentUser->department_id);
        } elseif ($departmentId && !$userId) {
            $query->where('department_id', $departmentId);
        }

        if ($userId) {
            $query->where('id', $userId);
        }

        $users = $query->orderBy('name')->get();

        return view('reports.print', compact('users', 'selectedMonth'));
    }

    /**
     * Generate and stream a PDF export of the attendance report.
     */
    public function export(Request $request)
    {
        /**
         * PHASE 1: DOCUMENT GENERATION & STREAMING
         * OBJECTIVE: Transform the standard print layout into a downloadable PDF format.
         * PROCEDURES: 
         * - Replicates the security and filtering logic found in the 'print' method.
         * - Utilizes DomPDF to render the 'reports.print' view as a binary file.
         */
        $selectedMonth = $request->input('month', now()->format('Y-m'));
        $departmentId = $request->input('department_id');
        $userId = $request->input('user_id'); 
        $currentUser = auth()->user();

        $query = \App\Models\User::with(['attendances' => function($q) use ($selectedMonth) {
            $q->whereMonth('date', \Carbon\Carbon::parse($selectedMonth)->month)
              ->whereYear('date', \Carbon\Carbon::parse($selectedMonth)->year)
              ->with('justification');
        }]);

        if ($currentUser->role?->name === 'HOD') {
            $query->where('department_id', $currentUser->department_id);
        } elseif ($departmentId && !$userId) {
            $query->where('department_id', $departmentId);
        }

        if ($userId) {
            $query->where('id', $userId);
        }

        $users = $query->orderBy('name')->get();

        $pdf = Pdf::loadView('reports.print', compact('users', 'selectedMonth'));
        
        return $pdf->download('Department_Attendance_Report.pdf');
    }
}