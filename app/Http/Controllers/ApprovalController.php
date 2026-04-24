<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApprovalController extends Controller
{
    // HOD fetching Staff requests
    public function index()
    {
        $hod = auth()->user();

        // Fetch pending attendances ONLY for Staff in the HOD's department
        $attendances = \App\Models\Attendance::with(['user', 'justification']) // Eager load user AND justification
            ->whereHas('justification', function ($query) {
                // Look inside the new justifications table for the pending status!
                $query->where('status', 'pending'); 
            })
            ->whereHas('user', function ($query) use ($hod) {
                // 1. Must match the HOD's department
                $query->where('department_id', $hod->department_id)
                      // 2. Must be a regular Staff member
                      ->whereHas('role', function($roleQuery) {
                          $roleQuery->where('name', 'Staff');
                      });
            })
            ->paginate(10);

        return view('hod.approvals', compact('attendances'));
    }

    public function approve($id)
    {
        // 1. Find the attendance record
        $attendance = Attendance::findOrFail($id);
        
        // 2. Update the status inside the attached JUSTIFICATION table!
        $attendance->justification()->update(['status' => 'approved']);

        // 3. Return to the page with a success message
        return back()->with('success', 'Staff discrepancy approved.');
    }

    public function reject($id)
    {
        // 1. Find the attendance record
        $attendance = Attendance::findOrFail($id);
        
        // 2. Update the status inside the attached JUSTIFICATION table!
        $attendance->justification()->update(['status' => 'rejected']);

        return back()->with('success', 'Staff discrepancy rejected.');
    }
}