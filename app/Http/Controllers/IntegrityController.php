<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <-- Added this so Auth::user() works!

class IntegrityController extends Controller
{
    // Integrity fetching HOD requests
    public function approvals()
    {
        // Fetch pending attendances ONLY for users with the HOD role
        $attendances = Attendance::with(['user', 'justification']) 
            ->whereHas('justification', function ($query) {
                // Look inside the new table for the pending status!
                $query->where('status', 'pending'); 
            })
            ->whereHas('user', function ($query) {
                $query->whereHas('role', function ($roleQuery) {
                    $roleQuery->where('name', 'HOD'); // Integrity only approves HODs
                });
            })
            ->paginate(10);

        return view('integrity.approvals', compact('attendances'));
    }

    public function approve($id)
    {
        $attendance = Attendance::findOrFail($id);
        
        // Update the status inside the new justification table!
        if ($attendance->justification) {
            $attendance->justification->update(['status' => 'approved']);
        }
        
        return back()->with('success', 'HOD discrepancy approved.');
    }

    public function reject($id)
    {
        $attendance = Attendance::findOrFail($id);
        
        // Update the status inside the new justification table
        if ($attendance->justification) {
            $attendance->justification->update(['status' => 'rejected']);
        }
        
        return back()->with('success', 'HOD discrepancy rejected.');
    }

    // Integrity Unit Dashboard
    public function integrityDashboard()
    {
        // 1. Get the currently logged-in Integrity officer
        $user = Auth::user();

        // 2. Count ONLY Pending Discrepancies submitted by HODs
        $totalPending = Attendance::whereHas('justification', function ($query) {
                $query->where('status', 'pending'); // <-- FIX: Check the new table!
            })
            ->whereHas('user', function ($query) {
                $query->whereHas('role', function($roleQuery) {
                    $roleQuery->where('name', 'HOD');
                });
            })
            ->count();

        // 3. Count system-wide Staff Attendance for Today
        $today = date('Y-m-d');
        $totalAttendanceToday = Attendance::whereDate('date', $today)
            ->whereNotNull('clock_in')
            ->count();

        // 4. Return the integrity dashboard view
        return view('integrity.dashboard', compact('user', 'totalPending', 'totalAttendanceToday'));
    }
}