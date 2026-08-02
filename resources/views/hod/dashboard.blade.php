@extends('layouts.app')

@section('title', 'HOD Dashboard')

{{-- 
  PHASE 1: WORKFLOW CONTEXT & HEADER
--}}
@section('page_title')
    Welcome, {{ $user->name ?? 'Head of Department' }}
@endsection
@section('page_subtitle', 'Manage departmental requests and monitor your attendance records.')

@section('content')

<div class="row g-4 mt-1">
    
    {{-- PHASE 2: ATTENDANCE CARD --}}
    <div class="col-md-6 col-xl-4">
        <div class="dashboard-card card-attendance pattern-zigzag h-100" style="min-height: 150px;">
            <div class="card-content">
                <div>
                    <p class="card-label">ATTENDANCE</p>
                    <h4 class="card-title">Attendance Records</h4>
                    <small class="card-desc">Review your attendance records and status updates</small>
                </div>
                <div class="card-icon">
                    <i class="bi bi-calendar-check"></i>
                </div>
            </div>
            <a href="{{ url('/attendance') }}" class="stretched-link" aria-label="View Attendance Records"></a>
        </div>
    </div>

    {{-- PHASE 3: PENDING APPROVALS CARD --}}
    <div class="col-md-6 col-xl-4">
        <div class="dashboard-card card-hod-approvals pattern-grid h-100" style="min-height: 150px;">
            <div class="card-content">
                <div>
                    <p class="card-label">APPROVAL OVERVIEW</p>
                    <h2 class="card-number">{{ $pendingApprovals ?? 0 }}</h2>
                    <small class="card-desc">Pending attendance and leave requests</small>
                </div>
                <div class="card-icon">
                    <i class="bi bi-check2-square"></i>
                </div>
            </div>
            <a href="{{ route('hod.approvals') }}" class="stretched-link" aria-label="View Pending Approval Requests"></a>
        </div>
    </div>

    {{-- PHASE 4: REPORTING CARD --}}
    <div class="col-md-6 col-xl-4">
        <div class="dashboard-card card-admin-reports h-100" style="min-height: 150px;">
            <div class="card-content">
                <div>
                    <p class="card-label">REPORTING</p>
                    <h2 class="card-title mb-2">Reports</h2>
                    <small class="card-desc">Generate and review department attendance reports</small>
                </div>
                <div class="card-icon">
                    <i class="bi bi-file-earmark-bar-graph"></i>
                </div>
            </div>
            <a href="{{ route('reports.index') }}" class="stretched-link" aria-label="View Reports"></a>
        </div>
    </div>

    {{-- PHASE 5: ACCOUNT SETTINGS CARD --}}
    <div class="col-md-6 col-xl-4">
        <div class="dashboard-card card-profile pattern-lines h-100" style="min-height: 150px;">
            <div class="card-content">
                <div>
                    <p class="card-label">ACCOUNT</p>
                    <h4 class="card-title">Profile Settings</h4>
                    <small class="card-desc">Manage your account information and credentials</small>
                </div>
                <div class="card-icon">
                    <i class="bi bi-person-gear"></i>
                </div>
            </div>
            <a href="{{ route('profile.edit') }}" class="stretched-link" aria-label="Manage Profile Settings"></a>
        </div>
    </div>

</div>

@endsection