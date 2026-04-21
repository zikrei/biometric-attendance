<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;

class IntegrityController extends Controller
{
    // Integrity fetching HOD requests
    public function approvals() // or index()
    {
        // Fetch pending attendances ONLY for users with the HOD role
        $attendances = \App\Models\Attendance::with('user')
            ->where('status', 'Pending')
            ->whereHas('user', function ($query) {
                $query->whereHas('role', function($roleQuery) {
                    $roleQuery->where('name', 'HOD');
                });
            })
            ->paginate(10);

        return view('integrity.approvals', compact('attendances'));
    }

    public function approve($id)
    {
        Attendance::findOrFail($id)->update(['status' => 'Approved']);
        return back()->with('success', 'HOD discrepancy approved.');
    }

    public function reject($id)
    {
        Attendance::findOrFail($id)->update(['status' => 'Rejected']);
        return back()->with('success', 'HOD discrepancy rejected.');
    }

    // Integrity Unit Dashboard
    public function integrityDashboard()
    {
        // 1. Get the currently logged-in Integrity officer
        $user = Auth::user();

        // 2. Count ONLY Pending Discrepancies submitted by HODs (Perfectly matches the approvals list!)
        $totalPending = Attendance::where('status', 'Pending')
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