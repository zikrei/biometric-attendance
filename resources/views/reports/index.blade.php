@extends('layouts.app')

@section('title', 'Generate Report')

@section('page_title', 'Attendance Report')

@section('page_subtitle', 'Generate and view attendance reports.')

@section('content')
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white border-0">
            <h5 class="mb-0">Generate Attendance Report</h5>
        </div>
        <div class="card-body">
            <!-- Filter Form -->
            <form action="{{ route('reports.generate') }}" method="GET">
                <div class="row g-4">
                    <!-- Date Range -->
                    <div class="col-md-4">
                        <label for="from_date" class="form-label">From Date</label>
                        <input type="date" name="from_date" id="from_date" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label for="to_date" class="form-label">To Date</label>
                        <input type="date" name="to_date" id="to_date" class="form-control" required>
                    </div>

                    <!-- Department Filter -->
                    <div class="col-md-4">
                        <label for="department" class="form-label">Department (optional)</label>
                        <select name="department" id="department" class="form-control">
                            <option value="">Select Department</option>
                            <option value="HR">HR</option>
                            <option value="IT">IT</option>
                            <option value="Finance">Finance</option>
                            <option value="Sales">Sales</option>
                        </select>
                    </div>
                </div>

                <div class="row g-4 mt-3">
                    <!-- User Filter -->
                    <div class="col-md-4">
                        <label for="user" class="form-label">User (optional)</label>
                        <select name="user" id="user" class="form-control">
                            <option value="">Select User</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mt-3 text-end">
                    <button type="submit" class="btn btn-primary">Generate Report</button>
                </div>
            </form>

            <!-- Report Table Section -->
            @if(isset($attendances))
                <div class="mt-5">
                    <table class="table table-striped table-bordered">
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

                <!-- Print / Export Buttons -->
                <div class="text-end mt-4">
                    <a href="{{ route('reports.print') }}" class="btn btn-secondary">Print</a>
                    <a href="{{ route('reports.export') }}" class="btn btn-danger">Export PDF</a>
                </div>
            @endif
        </div>
    </div>
@endsection