@extends('layouts.app')

@section('title', 'Pending Attendance Approvals')
@section('page_title', 'Pending Attendance Approvals')
@section('page_subtitle', 'Review and process attendance discrepancy requests.')

@section('content')
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-striped align-middle">
                    <thead class="table-light">
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
                                <td>{{ $attendance->justification->reason ?? '-' }}</td>
                                <td>
                                    @if($attendance->justification && $attendance->justification->attachment)
                                        <a href="{{ asset('storage/' . $attendance->justification->attachment) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-file-earmark-text"></i> View Document
                                        </a>
                                    @else
                                        <span class="text-muted small">No Document</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        {{-- Updated to Integrity Routes! --}}
                                        <form action="{{ route('integrity.approve', $attendance->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success">
                                                <i class="bi bi-check-lg"></i> Approve
                                            </button>
                                        </form>
                                        <form action="{{ route('integrity.reject', $attendance->id) }}" method="POST">
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
                                    There are currently no pending attendance requests requiring review.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection