<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Monthly Attendance Report</title>
    <style>
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

        .page-break {
            page-break-after: always;
            position: relative;
            min-height: 260mm; 
        }
        .page-break:last-child {
            page-break-after: auto;
        }

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

        .info-table {
            width: 100%;
            margin-bottom: 8px;
            border-bottom: 2px solid #000;
            padding-bottom: 8px;
        }
        .info-table td { font-size: 12px; }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 5px;
            table-layout: fixed; 
        }
        .data-table th, .data-table td {
            border: 1px solid #999;
            padding: 3px 4px; 
            text-align: center;
            vertical-align: middle;
        }
        .data-table th {
            background-color: #f0f0f0;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 9px;
        }
        
        .reason-col {
            text-align: left !important;
            font-size: 9px;
            line-height: 1.1; 
            overflow: hidden;
        }

        .weekend-row { background-color: #f9f9f9; color: #777; }

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
            bottom: 30px; 
            right: 30px;
            padding: 12px 24px;
            background: #0d6efd;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            box-shadow: 0 6px 12px rgba(0,0,0,0.2);
            z-index: 1000; 
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
                        <th style="width: 12%;">Hours</th>
                        <th style="width: 13%;">Status</th>
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
                            
                            // Look inside justification for reason and status
                            $reason = ($record && $record->justification) ? $record->justification->reason : '-';

                            // NEW: Hide the system-generated auto-approval message so the report stays clean
                            if (str_contains($reason, 'Auto-approved: Completed required working hours.')) {
                                $reason = '-';
                            }

                            $justificationStatus = ($record && $record->justification) ? strtolower($record->justification->status) : null;
                            $hours = '--';
                            $hoursWorked = 0;
                            
                            // Use floatDiffInHours for decimal calculation
                            if ($clockIn && $clockOut) {
                                $hoursWorked = \Carbon\Carbon::parse($clockIn)->floatDiffInHours(\Carbon\Carbon::parse($clockOut));
                            }

                            $displayStatus = 'Absent';

                            // 1. Determine the True Status
                            if ($isWeekend) {
                                $displayStatus = 'Weekend';
                            } else {
                                if ($justificationStatus && in_array($justificationStatus, ['pending', 'approved', 'rejected'])) {
                                    $displayStatus = ucfirst($justificationStatus);
                                } else {
                                    if (!$clockIn || !$clockOut || $hoursWorked < 9) {
                                        $displayStatus = 'Absent'; 
                                    } elseif ($hoursWorked >= 9) {
                                        $displayStatus = 'Approved';
                                    }
                                }
                            }

                            // 2. Handle Unapproved Time formatting
                            if ($displayStatus === 'Pending' || $displayStatus === 'Rejected') {
                                // Mask the hours so they don't get credit until it is approved
                                $hours = 'Unapproved'; 
                                
                                // Append an asterisk to the times so the admin knows they are proposed times
                                if ($clockIn) $clockIn .= '*';
                                if ($clockOut) $clockOut .= '*';
                            } elseif ($hoursWorked > 0) {
                                // Format valid hours to exactly 1 decimal point (e.g., 9.5h)
                                $hours = number_format($hoursWorked, 1) . 'h';
                            }

                            // 3. Clean up future dates
                            if ($loopDate->isFuture() && !$isWeekend) {
                                $displayStatus = '--';
                                $reason = '-';
                                $hours = '--';
                                $clockIn = '--:--';
                                $clockOut = '--:--';
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
                                @elseif($displayStatus === 'Absent' || $displayStatus === 'Rejected')
                                    <strong style="color: #b91c1c;">{{ $displayStatus }}</strong>
                                @elseif($displayStatus === 'Pending')
                                    <strong style="color: #b45309;">{{ $displayStatus }}</strong>
                                @else
                                    {{ $displayStatus }}
                                @endif
                            </td>
                            <td class="reason-col">{{ Str::limit($reason, 65) }}</td>
                        </tr>
                    @endfor
                </tbody>
            </table>

            <p style="font-size: 9px; color: #666; margin-top: 0;">
                <em>* Times marked with an asterisk are proposed times pending discrepancy approval.</em>
            </p>

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