@extends('layouts.app')
@section('title', 'Attendance Records')
@section('page_title', 'Pending Attendance Approvals')
@section('page_subtitle', 'Review and process attendance discrepancy requests.')

@section('content')
    <form action="{{ url()->current() }}" method="GET" class="d-flex gap-2 align-items-center mb-0">
        <input type="month" name="month" class="form-control" value="{{ $selectedMonth ?? now()->format('Y-m') }}" required>
        <button type="submit" class="btn btn-dark shadow-sm">Apply</button>
        <a href="{{ url()->current() }}" class="btn btn-outline-secondary shadow-sm">Clear</a>
        
        <a href="{{ route('attendance.print', ['month' => $selectedMonth ?? now()->format('Y-m')]) }}" target="_blank" class="btn btn-outline-primary ms-2 shadow-sm">
            <i class="bi bi-printer me-1"></i> Print
        </a>
    </form>
    <div class="card border-0 shadow-sm rounded-4 mt-3">
        <div class="card-body p-4">        
            <div class="table-responsive">
                <table class="table table-striped align-middle">
                    <thead class="table-light fs-5">
                        <tr>
                            <th>Date</th>
                            <th>Check-In Time</th>
                            <th>Check-Out Time</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $currentMonth = \Carbon\Carbon::parse($selectedMonth ?? now()->format('Y-m'));
                            $daysInMonth = $currentMonth->daysInMonth;
                            $attendanceMap = $attendances->keyBy('date');
                        @endphp

                        @for($i = 1; $i <= $daysInMonth; $i++)
                            @php
                                $loopDate = $currentMonth->copy()->day($i);
                                $dateString = $loopDate->format('Y-m-d');
                                $isWeekend = $loopDate->isWeekend();
                                $record = $attendanceMap->get($dateString);

                                $clockIn = $record->clock_in ?? null;
                                $clockOut = $record->clock_out ?? null;

                                $hoursWorked = 0;
                                if ($clockIn && $clockOut) {
                                    $hoursWorked = \Carbon\Carbon::parse($clockIn)->diffInHours(\Carbon\Carbon::parse($clockOut));
                                }

                                $needsAction = false;
                                $displayStatus = 'Absent';

                                if ($isWeekend) {
                                    $displayStatus = 'Weekend';
                                } else {
                                    $justificationStatus = ($record && $record->justification) ? strtolower($record->justification->status) : null;

                                    if ($justificationStatus && in_array($justificationStatus, ['pending', 'approved', 'rejected'])) {
                                        $displayStatus = ucfirst($justificationStatus);
                                        
                                        if ($justificationStatus === 'pending') {
                                            $displayStatus = 'Awaiting Approval';
                                        }
                                    } else {
                                        if (!$clockIn || !$clockOut) {
                                            $displayStatus = 'Need Discrepancy';
                                            $needsAction = true;
                                        } elseif ($hoursWorked < 9) {
                                            $displayStatus = 'Need Discrepancy';
                                            $needsAction = true;
                                        } elseif ($hoursWorked >= 9) {
                                            $displayStatus = 'Approved';
                                        }
                                    }
                                }
                                
                                if ($loopDate->isFuture() && !$isWeekend) {
                                    $displayStatus = '--';
                                    $needsAction = false;
                                }
                            @endphp

                            <tr class="{{ $isWeekend ? 'table-secondary text-muted' : '' }}">
                                <td class="fw-medium">{{ $loopDate->format('Y-m-d (l)') }}</td>
                                <td>{{ $clockIn ?? '--:--' }}</td>
                                <td>{{ $clockOut ?? '--:--' }}</td>
                                <td>
                                    @if(strtolower($displayStatus) === 'approved')
                                        <span class="badge bg-success border">🟢 Approved</span>
                                    @elseif(strtolower($displayStatus) === 'awaiting approval')
                                        <span class="badge bg-warning text-dark border">🟡 Awaiting Approval</span>
                                    @elseif(strtolower($displayStatus) === 'rejected')
                                        <span class="badge bg-danger border">🔴 Rejected</span>
                                    @elseif(strtolower($displayStatus) === 'need discrepancy')
                                        <span class="badge bg-danger text-white border">🔴 Need Discrepancy</span>
                                    @elseif(strtolower($displayStatus) === 'weekend')
                                        <span class="badge bg-light text-dark border">Weekend</span>
                                    @else
                                        <span class="badge bg-secondary border">{{ $displayStatus }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($needsAction)
                                        <a href="{{ $record ? route('attendance.edit', $record->id) : route('attendance.create', ['date' => $dateString]) }}" 
                                           class="btn btn-sm btn-primary text-white">
                                            <i class="bi bi-pencil-square"></i> Submit Discrepancy
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endfor
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection