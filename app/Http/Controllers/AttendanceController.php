<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance; 
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    // Show all attendance records (with month filter)
    public function index(Request $request)
    {
        $selectedMonth = $request->input('month', date('Y-m'));
        
        $parts = explode('-', $selectedMonth);
        $year = $parts[0];
        $month = $parts[1];

        $attendances = Attendance::where('user_id', auth()->id())
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->orderBy('date', 'desc')
            ->get();

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

    // Show form to edit attendance record / Request Discrepancy
    public function edit($id)
    {
        $attendance = Attendance::findOrFail($id);
        return view('attendance.edit', compact('attendance'));
    }

    // Update attendance record (Submit Discrepancy)
    public function update(Request $request, $id)
    {
        $attendance = Attendance::findOrFail($id);

        // 1. Validate inputs (Allowing PDF, JPG, PNG up to 10MB)
        $validated = $request->validate([
            'clock_in' => 'nullable',
            'clock_out' => 'nullable',
            'reason' => 'required|string|max:1000',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240', // 10MB limit
        ]);

        // 2. Prepare the data to update
        $updateData = [
            'clock_in' => $validated['clock_in'],
            'clock_out' => $validated['clock_out'],
            'reason' => $validated['reason'],
            'status' => 'Pending', // Resets for HOD approval
        ];

        // 3. Handle the File Upload
        if ($request->hasFile('attachment')) {
            // Stores the file inside storage/app/public/attachments
            $path = $request->file('attachment')->store('attachments', 'public');
            $updateData['attachment'] = $path;
        }

        // 4. Save to database
        $attendance->update($updateData);

        return redirect()->route('attendance.list')->with('success', 'Discrepancy request and attachment submitted successfully.');
    }

    // Show attendance history
    public function history()
    {
        return view('attendance.history');
    }
}