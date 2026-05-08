<!DOCTYPE html>
<html lang="en">
<head>
    {{-- 
      PHASE 1: DOCUMENT METADATA & DEPENDENCIES
      OBJECTIVE: Establish the technical foundation for the print view.
      RESOURCES: 
      - Leverages Bootstrap 5 for grid stability.
      - Connects to the global app.css for Phase 10 print rules (A4 sizing).
    --}}
    <meta charset="UTF-8">
    <title>Attendance Record - {{ $user->name }}</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

{{-- 
  PHASE 2: AUTOMATED PRINT INITIALIZATION
  OBJECTIVE: Streamline the administrative workflow by triggering the browser's print spooler immediately upon document load.
--}}
<body class="personal-print-layout" onload="window.print()">

    {{-- 
      PHASE 3: EMPLOYEE CONTEXT & REPORTING HEADER
      OBJECTIVE: Provide a clear audit trail identifying the subject and timeframe.
      PROCEDURE: Parses the $selectedMonth via Carbon to provide a formal "Month Year" string.
    --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Personal Attendance Record</h2>
            <p class="mb-0"><strong>Employee:</strong> {{ $user->name }}</p>
            <p class="mb-0"><strong>Month:</strong> {{ \Carbon\Carbon::parse($selectedMonth)->format('F Y') }}</p>
        </div>
        
        {{-- Manual trigger button, hidden on physical paper via the .no-print class --}}
        <button class="btn btn-primary no-print" onclick="window.print()">Print Document</button>
    </div>

    {{-- 
      PHASE 4: TABULAR GRID CONSTRUCTION
      OBJECTIVE: Present a daily log of check-in and check-out metrics.
    --}}
    <table class="table table-bordered table-striped align-middle">
        <thead class="table-light">
            <tr>
                <th>Date</th>
                <th>Check-In</th>
                <th>Check-Out</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @php
                /* PHASE 5: TEMPORAL DATA PROCESSING
                   OBJECTIVE: Generate a comprehensive row for every day in the month.
                   PROCEDURE: 
                   - Calculates the total days in the month.
                   - Maps attendance data to a keyed collection for instant lookup.
                */
                $currentMonth = \Carbon\Carbon::parse($selectedMonth);
                $daysInMonth = $currentMonth->daysInMonth;
                $attendanceMap = $attendances->keyBy('date');
            @endphp

            @for($i = 1; $i <= $daysInMonth; $i++)
                @php
                    $loopDate = $currentMonth->copy()->day($i);
                    $dateString = $loopDate->format('Y-m-d');
                    $isWeekend = $loopDate->isWeekend();
                    $record = $attendanceMap->get($dateString);
                    
                    /* Status Fallback Logic: Detects weekends vs. manual absences */
                    $displayStatus = $record->status ?? ($isWeekend ? 'Weekend' : 'Absent');
                @endphp
                
                {{-- Applies specific row styling for weekends for better physical scanability --}}
                <tr class="{{ $isWeekend ? 'table-secondary' : '' }}">
                    <td>{{ $loopDate->format('Y-m-d (l)') }}</td>
                    <td>{{ $record->clock_in ?? '--:--' }}</td>
                    <td>{{ $record->clock_out ?? '--:--' }}</td>
                    <td>{{ ucfirst($displayStatus) }}</td>
                </tr>
            @endfor
        </tbody>
    </table>

</body>
</html>