@extends('layouts.app')

@section('content')
    <div class="mb-4">
        <h2>Welcome {{ $user->name }}</h2>
        <p class="text-muted">Monitor company-wide attendance and compliance.</p>
    </div>

    <div class="row g-4">
        {{-- Company-wide Attendance Card --}}
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Company Attendance (Today)</p>
                        <h2 class="mb-0">{{ $totalAttendanceToday }}</h2>
                    </div>
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="bi bi-building fs-3"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Company-wide Pending Card --}}
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">System-wide Pending Approvals</p>
                        <h2 class="mb-0">{{ $totalPending }}</h2>
                    </div>
                    <div class="bg-warning bg-opacity-10 text-warning rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="bi bi-shield-exclamation fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection