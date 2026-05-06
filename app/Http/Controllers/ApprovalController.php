<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApprovalController extends Controller
{
    /**
     * Display a listing of pending attendance requests for the HOD's department.
     */
    public function index()
    {
        /**
         * PHASE 1: CONTEXTUAL AUTHENTICATION & SCOPE DEFINITION
         * OBJECTIVE: Identify the active Head of Department (HOD) to establish administrative boundaries.
         * PROCEDURES: Retrieve the authenticated user instance to access departmental and organizational metadata.
         */
        $hod = auth()->user();

        /**
         * PHASE 2: RELATIONAL QUERYING & DEPARTMENTAL FILTERING
         * OBJECTIVE: Retrieve a paginated collection of attendance discrepancies requiring review.
         * CRITERIA:
         * - Status: Filters for records where the associated justification is explicitly 'pending'.
         * - Department: Restricts results to users sharing the HOD's 'department_id'.
         * - Role: Targets 'Staff' members exclusively to maintain hierarchical approval integrity.
         * DATA OPTIMIZATION: Employs Eager Loading on 'user' and 'justification' to minimize database overhead.
         */
        $attendances = \App\Models\Attendance::with(['user', 'justification'])
            ->whereHas('justification', function ($query) {
                $query->where('status', 'pending'); 
            })
            ->whereHas('user', function ($query) use ($hod) {
                $query->where('department_id', $hod->department_id)
                      ->whereHas('role', function($roleQuery) {
                          $roleQuery->where('name', 'Staff');
                      });
            })
            ->paginate(10);

        return view('hod.approvals', compact('attendances'));
    }

    /**
     * Approve a specific attendance discrepancy.
     */
    public function approve($id)
    {
        /**
         * PHASE 3: RECORD IDENTIFICATION & STATE TRANSITION (APPROVAL)
         * OBJECTIVE: Formally authorize a pending attendance justification.
         * PROCEDURES: 
         * - Locate the Attendance record or trigger a 404 exception via 'findOrFail'.
         * - Execute a relational update on the 'justification' table to set the status to 'approved'.
         * FINALIZATION: Return the HOD to the previous interface with a session-based success confirmation.
         */
        $attendance = Attendance::findOrFail($id);
        
        $attendance->justification()->update(['status' => 'approved']);

        return back()->with('success', 'Staff discrepancy approved.');
    }

    /**
     * Reject a specific attendance discrepancy.
     */
    public function reject($id)
    {
        /**
         * PHASE 4: RECORD IDENTIFICATION & STATE TRANSITION (REJECTION)
         * OBJECTIVE: Formally decline a staff attendance discrepancy request.
         * PROCEDURES: 
         * - Validate record existence using the provided unique identifier.
         * - Transition the linked 'justification' status to 'rejected'.
         * FINALIZATION: Trigger a back-redirection with a failure/rejection notification.
         */
        $attendance = Attendance::findOrFail($id);
        
        $attendance->justification()->update(['status' => 'rejected']);

        return back()->with('success', 'Staff discrepancy rejected.');
    }
}