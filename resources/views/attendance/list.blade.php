@extends('layouts.app')

@section('title', 'Attendance Records')

@section('content')
    <div class="mb-4">
        <h2>Attendance Records</h2>
        <p class="text-muted">View and manage your attendance records efficiently.</p>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            
            {{-- Header, Filter Row & Print Button --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="mb-0">Attendance Records</h5>
                
                <form action="{{ url()->current() }}" method="GET" class="d-flex gap-2 align-items-center">
                    <input type="month" name="month" class="form-control" value="{{ $selectedMonth ?? now()->format('Y-m') }}" required>
                    <button type="submit" class="btn btn-dark">Apply</button>
                    <a href="{{ url()->current() }}" class="btn btn-outline-secondary">Clear</a>
                    
                    {{-- Updated Print Button to use the web.php route --}}
                    <a href="{{ route('attendance.print', ['month' => $selectedMonth ?? now()->format('Y-m')]) }}" target="_blank" class="btn btn-outline-primary ms-2">
                        <i class="bi bi-printer me-1"></i> Print
                    </a>
                </form>
            </div>

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
                                $displayStatus = $record->status ?? 'Absent';

                                if ($isWeekend) {
                                    $displayStatus = 'Weekend';
                                } else {
                                    if (!$clockIn || !$clockOut) {
                                        $displayStatus = 'Pending';
                                        $needsAction = true;
                                    } elseif ($hoursWorked < 8) {
                                        $needsAction = true;
                                    }
                                }
                            @endphp

                            <tr class="{{ $isWeekend ? 'table-secondary text-muted' : '' }}">
                                <td class="fw-medium">{{ $loopDate->format('Y-m-d (l)') }}</td>
                                <td>{{ $clockIn ?? '--:--' }}</td>
                                <td>{{ $clockOut ?? '--:--' }}</td>
                                <td>
                                    {{-- Colored Status Badges --}}
                                    @if(strtolower($displayStatus) === 'approved')
                                        <span class="badge bg-success text-white">Approved</span>
                                    @elseif(strtolower($displayStatus) === 'pending')
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @elseif(strtolower($displayStatus) === 'rejected')
                                        <span class="badge bg-danger text-white">Rejected</span>
                                    @elseif(strtolower($displayStatus) === 'weekend')
                                        <span class="badge bg-light text-dark border">Weekend</span>
                                    @else
                                        <span class="badge bg-secondary text-white">{{ ucfirst($displayStatus) }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($needsAction && !$isWeekend)
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