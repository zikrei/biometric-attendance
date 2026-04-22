<!DOCTYPE html>
<html>
<head>
    <title>Attendance Report</title>
    
    {{-- Connect your external app.css file --}}
    {{-- NOTE: If your PDF generator (like dompdf) ignores the CSS, change asset() to public_path() like this: --}}
    {{-- <link rel="stylesheet" href="{{ public_path('css/app.css') }}"> --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="pdf-layout">
    
    <div class="text-center">
        <h2>{{ config('app.name') }}</h2>
        <h3>Attendance Report Overview</h3>
        <p>Report for the Month of <strong>{{ \Carbon\Carbon::parse($monthInput)->format('F Y') }}</strong></p>
    </div>

    <table class="pdf-table">
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
                    <td>{{ $attendance->justification->status ?? 'Normal' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">No attendance records are available for the selected date range.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Replaced inline style with the new semantic class --}}
    <div class="pdf-signature-block">
        <p class="text-center">_______________________</p>
        <p class="text-center">Authorized Signature</p>
    </div>
    
</body>
</html>