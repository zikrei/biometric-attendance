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
}