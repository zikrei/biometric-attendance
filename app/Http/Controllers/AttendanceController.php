<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance; 
use App\Models\AttendanceJustification; 
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    /**
     * Display current user attendance records with automated compliance checks.
     */
    public function index(Request $request)
    {
        /**
         * PHASE 1: TEMPORAL SCOPE & FILTERING
         * OBJECTIVE: Define the active month and year for data retrieval.
         * PROCEDURES: Parses the 'month' input or defaults to the current date, splitting the string into Year/Month components.
         */
        $selectedMonth = $request->input('month', now()->format('Y-m'));
        $parts = explode('-', $selectedMonth);
        $year = $parts[0];
        $month = $parts[1];

        /**
         * PHASE 2: AUTONOMOUS COMPLIANCE EVALUATION
         * OBJECTIVE: Automatically approve records that meet minimum labor requirements without manual intervention.
         * CRITERIA: 
         * - Identifies records where both Clock-In and Clock-Out are present but lack a justification.
         * - Calculates duration; if >= 9 hours, generates a pre-approved justification record.
         */
        $unapprovedRecords = Attendance::where('user_id', auth()->id())
            ->whereNotNull('clock_in')
            ->whereNotNull('clock_out')
            ->doesntHave('justification') 
            ->get();

        foreach($unapprovedRecords as $record) {
            $hours = Carbon::parse($record->clock_in)->diffInHours(Carbon::parse($record->clock_out));
            
            if ($hours >= 9) {
                $record->justification()->create([
                    'status' => 'approved',
                    'reason' => 'Auto-approved: Completed required working hours.',
                ]);
            }
        }

        /**
         * PHASE 3: DATA AGGREGATION & EAGER LOADING
         * OBJECTIVE: Retrieve finalized records for user presentation.
         * OPTIMIZATION: Eager loads the 'justification' relationship to prevent N+1 query overhead during the list render.
         */
        $attendances = Attendance::with('justification')
            ->where('user_id', auth()->id())
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->orderBy('date', 'desc')
            ->get();

        return view('attendance.list', compact('attendances', 'selectedMonth'));
    }

    public function create(Request $request)
    {
        $date = $request->query('date');
        return view('attendance.create', compact('date'));
    }

    /**
     * Store a new attendance record and its associated justification.
     */
    public function store(Request $request)
    {
        /**
         * PHASE 1: PAYLOAD VALIDATION
         * OBJECTIVE: Sanitize and verify the integrity of the discrepancy submission.
         * CONSTRAINTS: Ensures the reason is provided and file attachments do not exceed 10MB.
         */
        $validated = $request->validate([
            'date' => 'required|date',
            'clock_in' => 'nullable',
            'clock_out' => 'nullable',
            'reason' => 'required|string|max:1000',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        /**
         * PHASE 2: BASE RECORD VERIFICATION
         * OBJECTIVE: Ensure a parent Attendance record exists before attaching a justification.
         * LOGIC: Employs 'firstOrCreate' to prevent duplicate primary entries for the same user-date combination.
         */
        $attendance = Attendance::firstOrCreate(
            ['user_id' => auth()->id(), 'date' => $validated['date']],
            ['clock_in' => $validated['clock_in'], 'clock_out' => $validated['clock_out']]
        );

        /**
         * PHASE 3: BINARY STORAGE & DISCREPANCY SUBMISSION
         * OBJECTIVE: Persist supporting evidence and formal excuses.
         * PROCEDURES: 
         * - Stores uploaded files in the 'attachments' public directory.
         * - Creates a linked justification record with a default 'pending' status for administrative review.
         */
        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('attachments', 'public');
        }

        $attendance->justification()->create([
            'status' => 'pending',
            'reason' => $validated['reason'],
            'attachment' => $attachmentPath,
        ]);

        return redirect()->route('attendance.list')->with('success', 'Discrepancy submitted successfully!');
    }

    public function edit($id)
    {
        $attendance = Attendance::with('justification')->findOrFail($id);
        return view('attendance.edit', compact('attendance'));
    }

    /**
     * Update an existing discrepancy and reset the approval lifecycle.
     */
    public function update(Request $request, $id)
    {
        $attendance = Attendance::findOrFail($id);

        /**
         * PHASE 1: SUPPLEMENTAL DATA CORRECTION
         * OBJECTIVE: Allow the user to adjust primary punch times during the discrepancy process.
         * PROCEDURES: Conditionally updates 'clock_in' or 'clock_out' only if new values are provided in the request.
         */
        $validated = $request->validate([
            'clock_in' => 'nullable',
            'clock_out' => 'nullable',
            'reason' => 'required|string|max:1000',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        if ($request->filled('clock_in') || $request->filled('clock_out')) {
            $attendance->update([
                'clock_in' => $validated['clock_in'] ?? $attendance->clock_in,
                'clock_out' => $validated['clock_out'] ?? $attendance->clock_out,
            ]);
        }

        /**
         * PHASE 2: ATTACHMENT MANAGEMENT & JUSTIFICATION RESYNCHRONIZATION
         * OBJECTIVE: Refresh the justification record while preserving existing evidence if no new file is provided.
         * RESET LOGIC: Resets the status to 'pending' to ensure the HOD reviews the updated documentation.
         */
        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('attachments', 'public');
        }

        $attendance->justification()->updateOrCreate(
            ['attendance_id' => $attendance->id],
            [
                'status' => 'pending',
                'reason' => $validated['reason'],
                'attachment' => $attachmentPath ?? $attendance->justification?->attachment 
            ]
        );

        return redirect()->route('attendance.list')->with('success', 'Discrepancy request and attachment submitted successfully.');
    }

    /**
     * Generate structured attendance reports for print.
     */
    public function print(Request $request)
    {
        /**
         * PHASE 1: REPORTING SCOPE INITIALIZATION
         * OBJECTIVE: Isolate data for the specific user and month requested for printing.
         * PROCEDURES: Eager loads justifications within a scoped closure to ensure only the relevant month's data is compiled for the report.
         */
        $selectedMonth = $request->input('month', now()->format('Y-m'));
        $currentUser = auth()->user();
        
        $users = \App\Models\User::with(['attendances' => function($q) use ($selectedMonth) {
            $q->whereMonth('date', \Carbon\Carbon::parse($selectedMonth)->month)
              ->whereYear('date', \Carbon\Carbon::parse($selectedMonth)->year)
              ->with('justification');
        }])->where('id', $currentUser->id)->get();

        return view('reports.print', compact('users', 'selectedMonth'));
    }
}