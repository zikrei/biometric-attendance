<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance; // <-- REQUIRED: Tells the controller where to find the Attendance table!

class AttendanceController extends Controller
{
    // Show all attendance records
    public function index(\Illuminate\Http\Request $request)
    {
        // 1. Get the month from the URL, or default to the current month (e.g., "2026-04")
        $selectedMonth = $request->input('month', date('Y-m'));
        
        // Split the "YYYY-MM" string into separate year and month variables
        $parts = explode('-', $selectedMonth);
        $year = $parts[0];
        $month = $parts[1];

        // 2. Fetch attendance securely: Only this user, matching the selected year and month
        $attendances = Attendance::where('user_id', auth()->id())
            ->whereYear('date', $year)   // Note: Change 'date' if your database column is named 'recordDate'
            ->whereMonth('date', $month)
            ->orderBy('date', 'desc')    // Sort newest to oldest
            ->get();

        // 3. Pass both the records and the currently selected month back to the view
        return view('attendance.list', compact('attendances', 'selectedMonth'));
    }

    // Show form to create new attendance record
    public function create()
    {
        return view('attendance.create');
    }

    // Store new attendance record
    public function store(Request $request)
    {
        // Logic to store attendance record
        return redirect()->route('attendance.list');
    }

    // Show form to edit attendance record
    public function edit($id)
    {
        return view('attendance.edit', compact('id'));
    }

    // Update attendance record
    public function update(Request $request, $id)
    {
        // Logic to update attendance record
        return redirect()->route('attendance.list');
    }

    // Show attendance history
    public function history()
    {
        return view('attendance.history');
    }
}