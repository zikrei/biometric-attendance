@extends('layouts.app')

@section('title', 'Pending Approvals')

@section('content')
    <div class="mb-4">
        <h2>Pending Approvals</h2>
        <p class="text-muted">Review and manage discrepancy requests.</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-striped align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Date</th>
                            <th>Clock In</th>
                            <th>Clock Out</th>
                            <th>Reason</th>
                            <th>Attachment</th>
                            <th>Action</th>
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
                                        <a href="{{ asset('storage/' . $attendance->attachment) }}" target="_blank" class="btn btn-sm btn-outline-info">View</a>
                                    @else
                                        <span class="text-muted small">None</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        {{-- CHANGE 'hod' to 'integrity' for the Integrity view --}}
                                        <form action="{{ route('hod.approve', $attendance->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success"><i class="bi bi-check-lg"></i></button>
                                        </form>
                                        <form action="{{ route('hod.reject', $attendance->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-x-lg"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">No pending requests to approve! 🎉</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection