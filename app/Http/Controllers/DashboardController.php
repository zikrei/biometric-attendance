<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; 
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;           

class DashboardController extends Controller
{
    /**
     * Compile personal attendance metrics and discrepancy status for the authenticated Staff user.
     */
    public function userDashboard()
    {
        /**
         * PHASE 1: IDENTITY RETRIEVAL
         * OBJECTIVE: Establish the session context for the active user.
         */
        $user = Auth::user();

        /**
         * PHASE 2: DISCREPANCY AUDITING
         * OBJECTIVE: Quantify unresolved attendance issues requiring user action.
         * PROCEDURES: Counts Attendance records linked to a 'pending' status in the justifications table.
         */
        $pendingDiscrepancies = Attendance::where('user_id', $user->id)
            ->whereHas('justification', function ($query) {
                $query->where('status', 'pending');
            })
            ->count();

        /**
         * PHASE 3: STATISTICAL ANALYSIS (MONTH-TO-DATE)
         * OBJECTIVE: Calculate the user's attendance reliability for the current calendar month.
         * METRICS: 
         * - Total Records: All generated attendance rows for the current Year/Month.
         * - Present Records: Rows containing a non-null 'clock_in' value.
         * - Percentage: Calculated as (Present / Total) * 100, with a zero-divisor safety check.
         */
        $currentMonth = date('m');
        $currentYear = date('Y');

        $totalRecords = Attendance::where('user_id', $user->id)
            ->whereMonth('date', $currentMonth)
            ->whereYear('date', $currentYear)
            ->count();

        $presentRecords = Attendance::where('user_id', $user->id)
            ->whereMonth('date', $currentMonth)
            ->whereYear('date', $currentYear)
            ->whereNotNull('clock_in')
            ->count();

        $attendancePercentage = $totalRecords > 0 ? round(($presentRecords / $totalRecords) * 100) : 0;

        return view('user.dashboard', compact('user', 'pendingDiscrepancies', 'attendancePercentage'));
    }

    /**
     * Compile system-wide administrative overview.
     */
    public function adminDashboard()
    {
        /**
         * PHASE 1: ADMINISTRATIVE CONTEXT & SYSTEM SCALE
         * OBJECTIVE: Provide a high-level snapshot of the platform's user base.
         * PROCEDURES: Authenticates the Admin and executes a global count of the User model.
         */
        $user = Auth::user();
        $totalUsers = \App\Models\User::count();

        return view('admin.dashboard', compact('user', 'totalUsers'));
    }

    /**
     * Compile departmental oversight metrics for the Head of Department.
     */
    public function hodDashboard()
    {
        /**
         * PHASE 1: DEPARTMENTAL SCOPE DEFINITION
         * OBJECTIVE: Identify the HOD's organizational unit to filter relevant staff data.
         */
        $user = Auth::user();

        /**
         * PHASE 2: PEER REVIEW QUEUE (PENDING APPROVALS)
         * OBJECTIVE: Identify subordinate justifications requiring HOD intervention.
         * FILTERING LOGIC: 
         * - Targets records with a 'pending' justification status.
         * - Restricts results to the HOD's department.
         * - Security: Explicitly excludes the HOD’s own attendance records from their approval queue.
         */
        $pendingApprovals = Attendance::whereHas('justification', function ($query) {
            $query->where('status', 'pending'); 
        })
        ->whereHas('user', function ($query) use ($user) {
            $query->where('department_id', $user->department_id)
                    ->where('id', '!=', $user->id); 
        })
        ->count();

        return view('hod.dashboard', compact('user', 'pendingApprovals'));
    }

    /**
     * Compile specialized compliance metrics for the Integrity Unit.
     */
    public function integrityDashboard()
    {
        /**
         * PHASE 1: HIGH-LEVEL DISCREPANCY TRACKING
         * OBJECTIVE: Monitor attendance justifications submitted specifically by Heads of Department.
         * PROCEDURES: Filters 'pending' justifications where the associated user possesses the 'HOD' role.
         */
        $user = Auth::user();

        $totalPending = Attendance::whereHas('justification', function ($query) {
            $query->where('status', 'pending');
        })
        ->whereHas('user.role', function ($query) {
            $query->where('name', 'HOD'); 
        })
        ->count();

        /**
         * PHASE 2: SYSTEM-WIDE OPERATIONAL SNAPSHOT
         * OBJECTIVE: View real-time attendance volume across the entire organization for the current date.
         * PROCEDURES: Counts all attendance entries for 'today' where a clock-in event has been recorded.
         */
        $today = date('Y-m-d');
        $totalAttendanceToday = Attendance::whereDate('date', $today)
            ->whereNotNull('clock_in')
            ->count();

        return view('integrity.dashboard', compact('user', 'totalPending', 'totalAttendanceToday'));
    }
}