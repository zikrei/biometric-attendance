<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Attendance Record - {{ $user->name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: white; color: black; padding: 20px; }
        @media print {
            .no-print { display: none !important; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Personal Attendance Record</h2>
            <p class="mb-0"><strong>Employee:</strong> {{ $user->name }}</p>
            <p class="mb-0"><strong>Month:</strong> {{ \Carbon\Carbon::parse($selectedMonth)->format('F Y') }}</p>
        </div>
        <button class="btn btn-primary no-print" onclick="window.print()">Print Document</button>
    </div>

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
                    $displayStatus = $record->status ?? ($isWeekend ? 'Weekend' : 'Absent');
                @endphp
                <tr>
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