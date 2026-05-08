@extends('layouts.app')

@section('title', 'Attendance Discrepancy Approval')

{{-- 
  PHASE 1: WORKFLOW CONTEXT & HEADER
  OBJECTIVE: Establish the scope of the discrepancy review interface.
  PROCEDURE: Provides a clear title and subtitle to guide HODs or Admins through the vetting process.
--}}
@section('page_title', 'Attendance Discrepancy Approval')

@section('page_subtitle', 'Review and take appropriate action on pending attendance discrepancy records.')

@section('content')
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white border-0">
            <h5 class="mb-0">Attendance Discrepancy Review</h5>
        </div>
        <div class="card-body">
            {{-- 
              PHASE 2: DISCREPANCY DATA REPRESENTATION
              OBJECTIVE: Display the specific timing errors and employee justifications in a high-readability grid.
              COLUMNS: Tracks the original record date, clock times, and the narrative reason for the discrepancy.
            --}}
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
                            
                            {{-- 
                              PHASE 3: CONDITIONAL STATUS VISUALIZATION
                              OBJECTIVE: Provide immediate visual feedback on the current lifecycle state of the request.
                              BADGES: Employs standard colors (Yellow for Pending, Green for Approved, Red for Rejected).
                            --}}
                            <td>
                                @if($discrepancy->status == 'Pending')
                                    <span class="badge bg-warning">🟡 Pending</span>
                                @elseif($discrepancy->status == 'Approved')
                                    <span class="badge bg-success">🟢 Approved</span>
                                @elseif($discrepancy->status == 'Rejected')
                                    <span class="badge bg-danger">🔴 Rejected</span>
                                @endif
                            </td>
                            
                            {{-- 
                              PHASE 4: ADMINISTRATIVE DECISION LOGIC
                              OBJECTIVE: Enable the reviewer to finalize the discrepancy status via secure POST transactions.
                              ACTIONS: Provides distinct buttons for authorization or denial of the justification request.
                            --}}
                            <td>
                                <form action="{{ route('attendance.approve', $discrepancy->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm w-100">Approve Request</button>
                                </form>
                                <form action="{{ route('attendance.reject', $discrepancy->id) }}" method="POST" class="mt-1">
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-sm w-100">Reject Request</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection