@extends('layouts.app')

@section('title', 'Attendance List')

@section('content')
    <div class="mb-4">
        <h2>Attendance List</h2>
        <p class="text-muted">View and manage your attendance records.</p>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            
            {{-- Header & Filter Row --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="mb-0">Your Attendance Records</h5>
                
                {{-- Month Filter Form --}}
                <form action="{{ url()->current() }}" method="GET" class="d-flex gap-2">
                    <input type="month" name="month" class="form-control" value="{{ $selectedMonth }}" required>
                    <button type="submit" class="btn btn-dark">Filter</button>
                    <a href="{{ url()->current() }}" class="btn btn-outline-secondary">Reset</a>
                </form>
            </div>

            {{-- Attendance Table --}}
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th>Clock In</th>
                            <th>Clock Out</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attendances as $attendance)
                            <tr>
                                <td>{{ $attendance->date }}</td> {{-- Change to recordDate if needed --}}
                                <td>{{ $attendance->clock_in ?? '--:--' }}</td>
                                <td>{{ $attendance->clock_out ?? '--:--' }}</td>
                                <td>
                                    @if($attendance->status == 'Pending')
                                        <span class="badge bg-warning text-dark">🟡 Pending</span>
                                    @elseif($attendance->status == 'Approved')
                                        <span class="badge bg-success">🟢 Approved</span>
                                    @elseif($attendance->status == 'Rejected')
                                        <span class="badge bg-danger">🔴 Rejected</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $attendance->status ?? 'N/A' }}</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('attendance.edit', $attendance->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil-square"></i> Discrepancy / Edit
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    No attendance records found for {{ \Carbon\Carbon::parse($selectedMonth)->format('F Y') }}.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
@endsection