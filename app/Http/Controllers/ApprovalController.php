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
        $user = Auth::user();

        // Fetch pending requests from the SAME department, EXCEPT the HOD themselves
        $attendances = Attendance::with('user')
            ->where('status', 'Pending')
            ->whereHas('user', function($query) use ($user) {
                $query->where('department_id', $user->department_id)
                      ->where('id', '!=', $user->id); // Exclude the logged-in HOD
            })->orderBy('date', 'desc')->get();

        return view('hod.approvals', compact('attendances'));
    }

    public function approve($id)
    {
        Attendance::findOrFail($id)->update(['status' => 'Approved']);
        return back()->with('success', 'Staff discrepancy approved.');
    }

    public function reject($id)
    {
        Attendance::findOrFail($id)->update(['status' => 'Rejected']);
        return back()->with('success', 'Staff discrepancy rejected.');
    }
}