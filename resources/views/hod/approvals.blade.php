@extends('layouts.app')

@section('title', 'Pending Attendance Approvals')

@section('content')
    <div class="mb-4">
        <h2>Pending Attendance Approvals</h2>
        <p class="text-muted">Review and process attendance discrepancy requests.</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            
            {{-- Header Row (Filter Removed) --}}
            {{-- <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="mb-0">Approval Queue</h5>
            </div> --}}

            <div class="table-responsive">
                <table class="table table-striped align-middle">
                    <thead class="table-light fs-5">
                        <tr>
                            <th>Employee Name</th>
                            <th>Date</th>
                            <th>Check-In Time</th>
                            <th>Check-Out Time</th>
                            <th>Reason for Discrepancy</th>
                            <th>Supporting Document</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attendances as $attendance)
                            <tr>
                                <td class="fw-bold">{{ $attendance->user->name }}</td>
                                <td>{{ $attendance->date }}</td>
                                <td>{{ $attendance->clock_in ?? '--:--' }}</td>
                                <td>{{ $attendance->clock_out ?? '--:--' }}</td>
                                <td>{{ $attendance->reason }}</td>
                                <td>
                                    @if($attendance->attachment)
                                        <a href="{{ asset('storage/' . $attendance->attachment) }}" target="_blank" class="btn btn-sm btn-outline-info">View Document</a>
                                    @else
                                        <span class="text-muted small">No Document</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <form action="{{ route('hod.approve', $attendance->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success">
                                                <i class="bi bi-check-lg"></i> Approve
                                            </button>
                                        </form>
                                        <form action="{{ route('hod.reject', $attendance->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="bi bi-x-lg"></i> Reject
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">
                                    There are currently no pending attendance requests requiring approval.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination Links --}}
            <div class="d-flex justify-content-center mt-4">
                {{ $attendances->links('pagination::bootstrap-5') }}
            </div>

        </div>
    </div>
@endsection