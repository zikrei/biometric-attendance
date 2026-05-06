<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IntegrityController extends Controller
{
    /**
     * Retrieve a filtered list of attendance discrepancies requiring Integrity Unit review.
     */
    public function approvals()
    {
        /**
         * PHASE 1: TARGETED HIERARCHICAL FILTERING
         * OBJECTIVE: Isolate pending justifications submitted specifically by Heads of Department (HODs).
         * PROCEDURES: 
         * - Filters for records where the justification status is explicitly 'pending'.
         * - Restricts result set to users possessing the 'HOD' role via nested relationship querying.
         * DATA OPTIMIZATION: Employs Eager Loading on 'user' and 'justification' to ensure efficient data rendering.
         */
        $attendances = Attendance::with(['user', 'justification']) 
            ->whereHas('justification', function ($query) {
                $query->where('status', 'pending'); 
            })
            ->whereHas('user', function ($query) {
                $query->whereHas('role', function ($roleQuery) {
                    $roleQuery->where('name', 'HOD');
                });
            })
            ->paginate(10);

        return view('integrity.approvals', compact('attendances'));
    }

    /**
     * Formally authorize an HOD's attendance discrepancy.
     */
    public function approve($id)
    {
        /**
         * PHASE 2: ADMINISTRATIVE ADJUDICATION (APPROVAL)
         * OBJECTIVE: Finalize and validate a submitted HOD attendance justification.
         * PROCEDURES: 
         * - Identifies the primary Attendance record or triggers a 404 failure.
         * - Executes a safe update on the linked justification model, transitioning the status to 'approved'.
         */
        $attendance = Attendance::findOrFail($id);
        
        if ($attendance->justification) {
            $attendance->justification->update(['status' => 'approved']);
        }
        
        return back()->with('success', 'HOD discrepancy approved.');
    }

    /**
     * Formally decline an HOD's attendance discrepancy.
     */
    public function reject($id)
    {
        /**
         * PHASE 3: ADMINISTRATIVE ADJUDICATION (REJECTION)
         * OBJECTIVE: Deny a discrepancy request and maintain record of the refusal.
         * PROCEDURES: Validates the existence of the record and the justification relation before setting the status to 'rejected'.
         */
        $attendance = Attendance::findOrFail($id);
        
        if ($attendance->justification) {
            $attendance->justification->update(['status' => 'rejected']);
        }
        
        return back()->with('success', 'HOD discrepancy rejected.');
    }

    /**
     * Compile specialized compliance metrics for the Integrity Unit Dashboard.
     */
    public function integrityDashboard()
    {
        /**
         * PHASE 4: OPERATIONAL OVERSIGHT & METRIC AGGREGATION
         * OBJECTIVE: Provide real-time visibility into high-level discrepancies and total system activity.
         * DATA POINTS:
         * - HOD Pending: Counts justifications with 'pending' status specifically from the HOD role.
         * - System-Wide Attendance: Quantifies all unique 'clock_in' events recorded across the organization for the current date.
         */
        $user = Auth::user();

        $totalPending = Attendance::whereHas('justification', function ($query) {
                $query->where('status', 'pending');
            })
            ->whereHas('user', function ($query) {
                $query->whereHas('role', function($roleQuery) {
                    $roleQuery->where('name', 'HOD');
                });
            })
            ->count();

        $today = date('Y-m-d');
        $totalAttendanceToday = Attendance::whereDate('date', $today)
            ->whereNotNull('clock_in')
            ->count();

        return view('integrity.dashboard', compact('user', 'totalPending', 'totalAttendanceToday'));
    }
}