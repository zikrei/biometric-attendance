<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApprovalController extends Controller
{
    // Show all pending attendance discrepancies for HOD
    public function index()
    {
        return view('hod.approvals');
    }

    // Approve attendance discrepancy
    public function approve($id)
    {
        // Logic to approve attendance
        return redirect()->route('hod.approvals');
    }

    // Reject attendance discrepancy
    public function reject($id)
    {
        // Logic to reject attendance
        return redirect()->route('hod.approvals');
    }
}