@extends('layouts.app')

@section('title', 'Attendance List')

@section('page_title', 'Attendance List')

@section('page_subtitle', 'View and manage your attendance records.')

@section('content')
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white border-0">
            <h5 class="mb-0">Your Attendance Records</h5>
        </div>
        <div class="card-body">
            <table class="table table-striped table-bordered">
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
                    @foreach($attendances as $attendance)
                        <tr>
                            <td>{{ $attendance->recordDate }}</td>
                            <td>{{ $attendance->clockIn }}</td>
                            <td>{{ $attendance->clockOut }}</td>
                            <td>
                                @if($attendance->status == 'Pending')
                                    <span class="badge bg-warning">🟡 Pending</span>
                                @elseif($attendance->status == 'Approved')
                                    <span class="badge bg-success">🟢 Approved</span>
                                @elseif($attendance->status == 'Rejected')
                                    <span class="badge bg-danger">🔴 Rejected</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('attendance.edit', $attendance->id) }}" class="btn btn-sm btn-primary">Edit</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection