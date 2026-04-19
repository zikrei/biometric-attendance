<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;

class IntegrityController extends Controller
{
    // Integrity fetching HOD requests
    public function approvals()
    {
        // Fetch pending requests ONLY from users who have the 'HOD' role
        $attendances = Attendance::with('user')
            ->where('status', 'Pending')
            ->whereHas('user.role', function($query) {
                $query->where('name', 'HOD');
            })->orderBy('date', 'desc')->get();

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