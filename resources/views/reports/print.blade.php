<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Monthly Attendance Report</title>
    <style>
        /* Force exact A4 dimensions and margins */
        @page {
            size: A4 portrait;
            margin: 15mm;
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #333;
            margin: 0;
            padding: 0;
            background: white;
        }

        /* Forces a new piece of A4 paper for every staff member */
        .page-break {
            page-break-after: always;
            position: relative;
            min-height: 260mm; 
        }
        .page-break:last-child {
            page-break-after: auto;
        }

        /* Header styling */
        .header {
            text-align: center;
            margin-bottom: 12px;
        }
        .header h2 {
            margin: 0 0 5px 0;
            font-size: 18px;
            text-transform: uppercase;
        }
        .header p {
            margin: 0;
            font-size: 13px;
            color: #555;
            font-weight: bold;
        }

        /* Employee Info Table */
        .info-table {
            width: 100%;
            margin-bottom: 8px;
            border-bottom: 2px solid #000;
            padding-bottom: 8px;
        }
        .info-table td { font-size: 12px; }

        /* Main 31-Day Table */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            table-layout: fixed; /* Forces strict column widths */
        }
        .data-table th, .data-table td {
            border: 1px solid #999;
            padding: 3px 4px; /* Ultra-tight padding to fit 31 rows + reasons */
            text-align: center;
            vertical-align: middle;
        }
        .data-table th {
            background-color: #f0f0f0;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 9px;
        }
        
        /* Reason Column specifics */
        .reason-col {
            text-align: left !important;
            font-size: 9px;
            line-height: 1.1; /* Keeps wrapped text tight */
            overflow: hidden;
        }

        /* Highlight weekends lightly */
        .weekend-row { background-color: #f9f9f9; color: #777; }

        /* Signatures */
        .footer-signature {
            width: 100%;
            margin-top: 25px;
        }
        .footer-signature td {
            width: 50%;
            text-align: center;
        }
        .signature-line {
            width: 200px;
            border-bottom: 1px solid #000;
            margin: 30px auto 5px;
        }

        .text-left { text-align: left !important; }
        
        @media print {
            .no-print { display: none !important; }
        }
        
        .print-btn {
            position: fixed;
            bottom: 30px; /* Changed from 'top' to 'bottom' */
            right: 30px;
            padding: 12px 24px;
            background: #0d6efd;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            box-shadow: 0 6px 12px rgba(0,0,0,0.2);
            z-index: 1000; /* Ensures it stays on top of everything */
        }
    </style>
</head>
<body>
    
    <button onclick="window.print()" class="print-btn no-print">🖨️ Print Document</button>

    @foreach($users as $user)
        <div class="page-break">
            
            <div class="header">
                <h2>Monthly Attendance Report</h2>
                <p>{{ \Carbon\Carbon::parse($selectedMonth)->format('F Y') }}</p>
            </div>

            <table class="info-table">
                <tr>
                    <td class="text-left"><strong>Name:</strong> {{ $user->name }}</td>
                    <td style="text-align: right;"><strong>Department:</strong> {{ $user->department->name ?? 'N/A' }}</td>
                </tr>
            </table>

            <table class="data-table">
                <thead>
                    <tr>
                        <th class="text-left" style="width: 18%;">Date</th>
                        <th style="width: 11%;">Check-In</th>
                        <th style="width: 11%;">Check-Out</th>
                        <th style="width: 9%;">Hours</th>
                        <th style="width: 16%;">Status</th>
                        <th class="text-left" style="width: 35%;">Discrepancy Reason</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $currentMonth = \Carbon\Carbon::parse($selectedMonth);
                        $daysInMonth = $currentMonth->daysInMonth;
                        $attendanceMap = $user->attendances->keyBy('date');
                    @endphp

                    @for($i = 1; $i <= $daysInMonth; $i++)
                        @php
                            $loopDate = $currentMonth->copy()->day($i);
                            $dateString = $loopDate->format('Y-m-d');
                            $isWeekend = $loopDate->isWeekend();
                            $record = $attendanceMap->get($dateString);

                            $clockIn = $record->clock_in ?? null;
                            $clockOut = $record->clock_out ?? null;
                            $reason = $record->reason ?? '-';
                            
                            $hours = '--';
                            if ($clockIn && $clockOut) {
                                $hours = \Carbon\Carbon::parse($clockIn)->diffInHours(\Carbon\Carbon::parse($clockOut)) . 'h';
                            }

                            $displayStatus = 'Absent';
                            if ($isWeekend) {
                                $displayStatus = 'Weekend';
                            } elseif ($record) {
                                if (strtolower($record->status) === 'pending') {
                                    $displayStatus = 'Pending';
                                } else {
                                    $displayStatus = ucfirst($record->status);
                                }
                            }

                            if ($loopDate->isFuture() && !$isWeekend) {
                                $displayStatus = '--';
                                $reason = '-';
                            }
                        @endphp
                        
                        <tr class="{{ $isWeekend ? 'weekend-row' : '' }}">
                            <td class="text-left">{{ $loopDate->format('d M Y (D)') }}</td>
                            <td>{{ $clockIn ?? '--:--' }}</td>
                            <td>{{ $clockOut ?? '--:--' }}</td>
                            <td>{{ $hours }}</td>
                            <td>
                                @if($displayStatus === 'Approved')
                                    <strong style="color: #15803d;">{{ $displayStatus }}</strong>
                                @elseif($displayStatus === 'Absent')
                                    <strong style="color: #b91c1c;">{{ $displayStatus }}</strong>
                                @elseif($displayStatus === 'Pending')
                                    <strong style="color: #b45309;">{{ $displayStatus }}</strong>
                                @else
                                    {{ $displayStatus }}
                                @endif
                            </td>
                            {{-- NEW DISCREPANCY REASON COLUMN --}}
                            <td class="reason-col">{{ Str::limit($reason, 65) }}</td>
                        </tr>
                    @endfor
                </tbody>
            </table>

            <table class="footer-signature">
                <tr>
                    <td>
                        <div class="signature-line"></div>
                        <p><strong>Employee Signature</strong></p>
                    </td>
                    <td>
                        <div class="signature-line"></div>
                        <p><strong>HOD / Authorized Signature</strong></p>
                    </td>
                </tr>
            </table>
            
        </div>
    @endforeach

    <script>
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        };
    </script>
</body>
</html>