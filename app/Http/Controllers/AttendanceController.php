<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance; 
use App\Models\AttendanceJustification; // NEW: Import the Justification Model!
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    // Show all attendance records (with month filter)
    public function index(Request $request)
    {
        $selectedMonth = $request->input('month', now()->format('Y-m'));
        $parts = explode('-', $selectedMonth);
        $year = $parts[0];
        $month = $parts[1];

        // 1. AUTO-APPROVE LOGIC
        // Find records with both punches that DON'T have a justification yet
        $unapprovedRecords = Attendance::where('user_id', auth()->id())
            ->whereNotNull('clock_in')
            ->whereNotNull('clock_out')
            ->doesntHave('justification') // Eloquent magic: Ignores records that already have a justification
            ->get();

        foreach($unapprovedRecords as $record) {
            $hours = Carbon::parse($record->clock_in)->diffInHours(Carbon::parse($record->clock_out));
            
            // If they worked 9 or more hours, instantly create an Approved Justification
            if ($hours >= 9) {
                $record->justification()->create([
                    'status' => 'approved',
                    'reason' => 'Auto-approved: Completed required working hours.',
                ]);
            }
        }

        // 2. FETCH ATTENDANCES (Eager loading the new justifications!)
        $attendances = Attendance::with('justification')
            ->where('user_id', auth()->id())
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->orderBy('date', 'desc')
            ->get();

        return view('attendance.list', compact('attendances', 'selectedMonth'));
    }

    // Show form to create new attendance record (Missing Punch / Absent)
    public function create(Request $request)
    {
        $date = $request->query('date');
        return view('attendance.create', compact('date'));
    }

    // Store new attendance record
    public function store(Request $request)
    {
        // 1. Validate inputs
        $validated = $request->validate([
            'date' => 'required|date',
            'clock_in' => 'nullable',
            'clock_out' => 'nullable',
            'reason' => 'required|string|max:1000',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        // 2. Ensure the base Attendance record exists for this date
        $attendance = Attendance::firstOrCreate(
            ['user_id' => auth()->id(), 'date' => $validated['date']],
            ['clock_in' => $validated['clock_in'], 'clock_out' => $validated['clock_out']]
        );

        // 3. Handle File Upload
        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('attachments', 'public');
        }

        // 4. Save to the NEW Justifications Table
        $attendance->justification()->create([
            'status' => 'pending',
            'reason' => $validated['reason'],
            'attachment' => $attachmentPath,
        ]);

        return redirect()->route('attendance.list')->with('success', 'Discrepancy submitted successfully!');
    }

    // Show form to edit attendance record / Request Discrepancy
    public function edit($id)
    {
        // Fetch the attendance AND its justification if it has one
        $attendance = Attendance::with('justification')->findOrFail($id);
        
        return view('attendance.edit', compact('attendance'));
    }

    // Update attendance record (Submit Discrepancy)
    public function update(Request $request, $id)
    {
        $attendance = Attendance::findOrFail($id);

        // 1. Validate inputs
        $validated = $request->validate([
            'clock_in' => 'nullable',
            'clock_out' => 'nullable',
            'reason' => 'required|string|max:1000',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        // 2. Optional: If they are requesting to fix a missing time, we can update the base table
        if ($request->filled('clock_in') || $request->filled('clock_out')) {
            $attendance->update([
                'clock_in' => $validated['clock_in'] ?? $attendance->clock_in,
                'clock_out' => $validated['clock_out'] ?? $attendance->clock_out,
            ]);
        }

        // 3. Handle the File Upload
        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('attachments', 'public');
        }

        // 4. Update or Create the Justification!
        // This ensures they don't create duplicate reasons if they submit twice
        $attendance->justification()->updateOrCreate(
            ['attendance_id' => $attendance->id],
            [
                'status' => 'pending', // Resets to pending so HOD can review the new excuse
                'reason' => $validated['reason'],
                // If they didn't upload a new file, keep the old one!
                'attachment' => $attachmentPath ?? $attendance->justification?->attachment 
            ]
        );

        return redirect()->route('attendance.list')->with('success', 'Discrepancy request and attachment submitted successfully.');
    }

    // Show attendance history
    public function history()
    {
        return view('attendance.history');
    }

    // Print method (Staff - Uses Universal Template)
    public function print(Request $request)
    {
        $selectedMonth = $request->input('month', now()->format('Y-m'));
        $currentUser = auth()->user();
        
        $users = \App\Models\User::with(['attendances' => function($q) use ($selectedMonth) {
            $q->whereMonth('date', \Carbon\Carbon::parse($selectedMonth)->month)
              ->whereYear('date', \Carbon\Carbon::parse($selectedMonth)->year)
              ->with('justification'); // <-- NEW: Eager load justifications for the print layout
        }])->where('id', $currentUser->id)->get();

        return view('reports.print', compact('users', 'selectedMonth'));
    }
}