@extends('layouts.app')

@section('title', 'Report Preview')

@section('page_title', 'Attendance Report Preview')

@section('page_subtitle', 'Preview the attendance report before printing or exporting.')

@section('content')
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white border-0">
            <h5 class="mb-0">Attendance Report</h5>
        </div>
        <div class="card-body">
            <div class="mb-5">
                <!-- Company Header -->
                <div class="text-center">
                    <h2>Company Name</h2>
                    <h5>Attendance Report</h5>
                    <p>From: {{ $from_date }} To: {{ $to_date }}</p>
                </div>

                <!-- Report Table -->
                <table class="table table-bordered mt-4">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Department</th>
                            <th>Date</th>
                            <th>Check-in</th>
                            <th>Check-out</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($attendances as $attendance)
                            <tr>
                                <td>{{ $attendance->user->name }}</td>
                                <td>{{ $attendance->user->department }}</td>
                                <td>{{ $attendance->date }}</td>
                                <td>{{ $attendance->clock_in }}</td>
                                <td>{{ $attendance->clock_out }}</td>
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

            <!-- Signature Section -->
            <div class="mt-5">
                <p class="text-center">_______________________</p>
                <p class="text-center">Signature</p>
            </div>

            <!-- Print / Export Buttons -->
            <div class="text-end mt-4">
                <a href="{{ route('reports.print') }}" class="btn btn-secondary">Print</a>
                <a href="{{ route('reports.export') }}" class="btn btn-danger">Export PDF</a>
            </div>
        </div>
    </div>
@endsection