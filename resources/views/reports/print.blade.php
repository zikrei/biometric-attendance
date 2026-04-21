<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Monthly Attendance Report</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: white; color: black; font-size: 14px; }
        
        /* This forces a new page for every user when printing to PDF! */
        .user-page { page-break-after: always; padding: 20px; }
        .user-page:last-child { page-break-after: auto; }
        
        @media print {
            .no-print { display: none !important; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="p-3 text-end no-print">
        <button class="btn btn-primary" onclick="window.print()">Print / Save as PDF</button>
    </div>

    @forelse($users as $user)
        <div class="user-page">
            <div class="mb-4 border-bottom pb-3">
                <h3 class="mb-1">Attendance Record</h3>
                <p class="mb-0 fs-5"><strong>Employee:</strong> {{ $user->name }}</p>
                <p class="mb-0 text-muted"><strong>Month:</strong> {{ \Carbon\Carbon::parse($selectedMonth)->format('F Y') }}</p>
                @if($user->department)
                    <p class="mb-0 text-muted"><strong>Department:</strong> {{ $user->department->name ?? 'N/A' }}</p>
                @endif
            </div>

            <table class="table table-bordered table-sm align-middle text-center">
                <thead class="table-light">
                    <tr>
                        <th class="text-start">Date</th>
                        <th>Check-In</th>
                        <th>Check-Out</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $currentMonth = \Carbon\Carbon::parse($selectedMonth);
                        $daysInMonth = $currentMonth->daysInMonth;
                        // Key the attendances by date for this specific user
                        $attendanceMap = $user->attendances->keyBy('date');
                    @endphp

                    @for($i = 1; $i <= $daysInMonth; $i++)
                        @php
                            $loopDate = $currentMonth->copy()->day($i);
                            $dateString = $loopDate->format('Y-m-d');
                            $isWeekend = $loopDate->isWeekend();
                            $record = $attendanceMap->get($dateString);
                            
                            $displayStatus = $record->status ?? ($isWeekend ? 'Weekend' : 'Absent');
                        @endphp
                        <tr class="{{ $isWeekend ? 'table-secondary text-muted' : '' }}">
                            <td class="text-start fw-medium">{{ $loopDate->format('Y-m-d (l)') }}</td>
                            <td>{{ $record->clock_in ?? '--:--' }}</td>
                            <td>{{ $record->clock_out ?? '--:--' }}</td>
                            <td>{{ ucfirst($displayStatus) }}</td>
                        </tr>
                    @endfor
                </tbody>
            </table>
        </div>
    @empty
        <div class="text-center p-5">
            <h4>No records found for the selected criteria.</h4>
        </div>
    @endforelse

</body>
</html>