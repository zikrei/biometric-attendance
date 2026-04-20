@extends('layouts.app')

@section('title', 'Attendance Discrepancy Approval')

@section('page_title', 'Attendance Discrepancy Approval')

@section('page_subtitle', 'Review and take appropriate action on pending attendance discrepancy records.')

@section('content')
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white border-0">
            <h5 class="mb-0">Attendance Discrepancy Review</h5>
        </div>
        <div class="card-body">
            <table class="table table-striped table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th>Check-In Time</th>
                        <th>Check-Out Time</th>
                        <th>Reason for Discrepancy</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($discrepancies as $discrepancy)
                        <tr>
                            <td>{{ $discrepancy->recordDate }}</td>
                            <td>{{ $discrepancy->clockIn }}</td>
                            <td>{{ $discrepancy->clockOut }}</td>
                            <td>{{ $discrepancy->reason }}</td>
                            <td>
                                @if($discrepancy->status == 'Pending')
                                    <span class="badge bg-warning">🟡 Pending</span>
                                @elseif($discrepancy->status == 'Approved')
                                    <span class="badge bg-success">🟢 Approved</span>
                                @elseif($discrepancy->status == 'Rejected')
                                    <span class="badge bg-danger">🔴 Rejected</span>
                                @endif
                            </td>
                            <td>
                                <form action="{{ route('attendance.approve', $discrepancy->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm">Approve Request</button>
                                </form>
                                <form action="{{ route('attendance.reject', $discrepancy->id) }}" method="POST" class="mt-1">
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-sm">Reject Request</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection