@extends('layouts.app')

@section('title', 'Attendance Report Preview')

@section('page_title', 'Attendance Report Preview')

@section('page_subtitle', 'Preview the attendance report before generating a printable or exportable version.')

@section('content')
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white border-0">
            <h5 class="mb-0">Attendance Report</h5>
        </div>
        <div class="card-body">
            <div class="mb-5">
                <div class="text-center">
                    <h2>{{ config('app.name') }}</h2>
                    <h5>Attendance Report</h5>
                    <p class="text-muted fw-bold">Report for the month of {{ \Carbon\Carbon::parse($monthInput)->format('F Y') }}</p>
                </div>

                <table class="table table-bordered mt-4">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Department</th>
                            <th>Date</th>
                            <th>Check-In</th>
                            <th>Check-Out</th>
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
                                <td>
                                    @if(strtolower($attendance->status) == 'pending')
                                        <span class="badge bg-warning text-dark">🟡 Awaiting Approval</span>
                                    @elseif(strtolower($attendance->status) == 'approved')
                                        <span class="badge bg-success">🟢 Approved</span>
                                    @elseif(strtolower($attendance->status) == 'rejected')
                                        <span class="badge bg-danger">🔴 Rejected</span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($attendance->status) }}</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">No attendance records available for the selected date range.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-5">
                <p class="text-center">_______________________</p>
                <p class="text-center">Signature</p>
            </div>

            <div class="text-end mt-4">
                <a href="{{ route('reports.print', request()->query()) }}" class="btn btn-secondary">Print Report</a>
                <a href="{{ route('reports.export', request()->query()) }}" class="btn btn-danger">Export as PDF</a>
            </div>
        </div>
    </div>
@endsection