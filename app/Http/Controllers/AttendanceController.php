<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance; 
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

        // 1. AUTO-APPROVE LOGIC (Run this BEFORE fetching the records for the view!)
        // Find records with both punches that haven't entered the discrepancy workflow yet
        $unapprovedRecords = Attendance::where('user_id', auth()->id())
            ->whereNotNull('clock_in')
            ->whereNotNull('clock_out')
            ->where(function($q) {
                // Strictly touch blank records only. 
                // Ignores all Pending, Approved, and Rejected statuses automatically!
                $q->whereNull('status')
                  ->orWhere('status', '');
            })
            ->get();

        foreach($unapprovedRecords as $record) {
            $hours = Carbon::parse($record->clock_in)->diffInHours(Carbon::parse($record->clock_out));
            
            // If they worked 9 or more hours, instantly update the Database Status to Approved
            if ($hours >= 9) {
                $record->update(['status' => 'Approved']);
            }
        }

        // 2. FETCH ATTENDANCES (Now fetching the freshly updated statuses!)
        $attendances = Attendance::where('user_id', auth()->id())
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->orderBy('date', 'desc')
            ->get();

        return view('attendance.list', compact('attendances', 'selectedMonth'));
    }

    // Show form to create new attendance record (Missing Punch / Absent)
    public function create(Request $request)
    {
        // Grab the date from the URL (e.g., ?date=2026-01-02)
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

        // 2. Prepare data
        $data = [
            'user_id' => auth()->id(), // Automatically link to the logged-in user
            'date' => $validated['date'],
            'clock_in' => $validated['clock_in'],
            'clock_out' => $validated['clock_out'],
            'reason' => $validated['reason'],
            'status' => 'Pending', // Sets status so HOD can review it
        ];

        // 3. Handle File Upload
        if ($request->hasFile('attachment')) {
            $data['attachment'] = $request->file('attachment')->store('attachments', 'public');
        }

        // 4. Save to Database
        Attendance::create($data);

        return redirect()->route('attendance.list')->with('success', 'Discrepancy submitted successfully!');
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

    // Print method
    public function print(Request $request)
    {
        $selectedMonth = $request->input('month', now()->format('Y-m'));
        $user = auth()->user();
        
        $attendances = Attendance::where('user_id', $user->id)
            ->whereMonth('date', Carbon::parse($selectedMonth)->month)
            ->whereYear('date', Carbon::parse($selectedMonth)->year)
            ->get();

        return view('attendance.print', compact('attendances', 'selectedMonth', 'user'));
    }
}