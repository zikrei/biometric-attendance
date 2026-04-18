<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Attendance Report</title>
    <style>
        /* Printer-friendly styling */
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: 14px; padding: 20px; color: black; background: white; }
        .text-center { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #f8f9fa; font-weight: bold; }
        
        /* Hide buttons when the page is actually printing */
        @media print {
            .no-print { display: none !important; }
            body { padding: 0; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="no-print" style="margin-bottom: 30px; text-align: right;">
        <button onclick="window.history.back()" style="padding: 8px 16px; background: #6c757d; color: white; border: none; border-radius: 4px; cursor: pointer; margin-right: 10px;">Go Back</button>
        <button onclick="window.print()" style="padding: 8px 16px; background: #0d6efd; color: white; border: none; border-radius: 4px; cursor: pointer;">Print Again</button>
    </div>

    <div class="text-center">
        <h2>Company Name</h2>
        <h3>Attendance Report</h3>
        <p>From: <strong>{{ $from_date }}</strong> To: <strong>{{ $to_date }}</strong></p>
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
                    <td colspan="6" class="text-center">No attendance records found for this date range.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 60px;">
        <p class="text-center">_______________________</p>
        <p class="text-center">Authorized Signature</p>
    </div>

</body>
</html>