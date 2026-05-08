@extends('layouts.app')

@section('title', 'Attendance History')

{{-- 
  PHASE 1: CONTEXTUAL HEADER & IDENTITY
  OBJECTIVE: Define the scope of the user's historical biometric record.
  PROCEDURE: Provides a clear navigational anchor and descriptive subtitle for the employee's personal log.
--}}
@section('page_title', 'Attendance History')

@section('page_subtitle', 'View all recorded attendance entries.')

@section('content')
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white border-0">
            <h5 class="mb-0">Attendance Records</h5>
        </div>
        <div class="card-body">
            {{-- 
              PHASE 2: TABULAR RECORD STRUCTURE
              OBJECTIVE: Present historical clock-in/out data in a high-readability grid format.
              CONFIGURATION: Employs standard Bootstrap table classes for consistent styling across the dashboard.
            --}}
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
                    {{-- 
                      PHASE 3: PERSONAL DATA ITERATION
                      OBJECTIVE: Map the $attendanceHistory collection to individual table rows.
                      ATTRIBUTES: Specifically tracks the recordDate, clockIn, and clockOut temporal data points.
                    --}}
                    @foreach($attendanceHistory as $attendance)
                        <tr>
                            <td>{{ $attendance->recordDate }}</td>
                            <td>{{ $attendance->clockIn }}</td>
                            <td>{{ $attendance->clockOut }}</td>
                            
                            {{-- 
                              PHASE 4: STATUS LIFECYCLE VISUALIZATION
                              OBJECTIVE: Provide visual feedback on the vetting status of each attendance entry.
                              LOGIC: Employs color-coded badges to indicate if a record is awaiting review (Yellow), finalized (Green), or denied (Red).
                            --}}
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