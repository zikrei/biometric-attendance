@extends('layouts.app')

@section('title', 'Attendance History')

@section('page_title', 'Attendance History')

@section('page_subtitle', 'View all recorded attendance entries.')

@section('content')
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white border-0">
            <h5 class="mb-0">Attendance Records</h5>
        </div>
        <div class="card-body">
            <table class="table table-striped table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th>Check-In Time</th>
                        <th>Check-Out Time</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($attendanceHistory as $attendance)
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
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection