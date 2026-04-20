<!DOCTYPE html>
<html>
<head>
    <title>Attendance Report</title>
    <style>
        body { font-family: sans-serif; font-size: 14px; }
        .text-center { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #333; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <div class="text-center">
        <h2>{{ config('app.name') }}</h2>
        <h3>Attendance Report Overview</h3>
        <p>Report for the Month of <strong>{{ \Carbon\Carbon::parse($monthInput)->format('F Y') }}</strong></p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Department</th>
                <th>Date</th>
                <th>Check-in</th>
                <th>Check-out</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($attendances as $attendance)
                <tr>
                    <td>{{ $attendance->user->name }}</td>
                    <td>{{ $attendance->user->department?->name ?? 'N/A' }}</td>
                    <td>{{ $attendance->date }}</td>
                    <td>{{ $attendance->clock_in }}</td>
                    <td>{{ $attendance->clock_out }}</td>
                    <td>{{ $attendance->status }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">No attendance records are available for the selected date range.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 50px;">
        <p class="text-center">_______________________</p>
        <p class="text-center">Authorized Signature</p>
    </div>
</body>
</html>