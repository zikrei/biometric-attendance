@extends('layouts.app')

@section('title', 'HOD Overview')

{{-- Push the Welcome message directly into the Top Header --}}
@section('page_title')
    Welcome back, {{ $user->name ?? 'HOD' }} 👋
@endsection

@section('page_subtitle', 'Manage your department\'s requests and track your own attendance.')

@section('content')

<div class="row g-4 mt-1">
    
    {{-- 1. Pending Approvals Card (Orange Gradient) --}}
    <div class="col-md-6 col-xl-4">
        <div class="dashboard-card card-hod-approvals pattern-grid h-100">
            <div class="card-content">
                <div>
                    <p class="card-label">Department Actions</p>
                    <h2 class="card-number">{{ $pendingApprovals ?? 0 }}</h2>
                    <small class="card-desc">Pending staff leave/attendance approvals</small>
                </div>
                <div class="card-icon">
                    <i class="bi bi-check2-square"></i>
                </div>
            </div>
            <a href="{{ route('hod.approvals') }}" class="stretched-link" aria-label="Review Pending Approvals"></a>
        </div>
    </div>

    {{-- 2. My Attendance Card (Cyan Gradient) --}}
    <div class="col-md-6 col-xl-4">
        <div class="dashboard-card card-attendance pattern-zigzag h-100">
            <div class="card-content">
                <div>
                    <p class="card-label">Daily Log</p>
                    <h4 class="card-title">Attendance Log</h4>
                    <small class="card-desc">Review your personal check-ins and statuses</small>
                </div>
                <div class="card-icon">
                    <i class="bi bi-calendar-check"></i>
                </div>
            </div>
            <a href="{{ url('/attendance') }}" class="stretched-link" aria-label="View My Attendance Log"></a>
        </div>
    </div>

    {{-- 3. Account Settings Card (Rose Gradient) --}}
    <div class="col-md-6 col-xl-4">
        <div class="dashboard-card card-profile pattern-lines h-100">
            <div class="card-content">
                <div>
                    <p class="card-label">Configuration</p>
                    <h4 class="card-title">Account Settings</h4>
                    <small class="card-desc">Update your credentials and profile</small>
                </div>
                <div class="card-icon">
                    <i class="bi bi-person-gear"></i>
                </div>
            </div>
            <a href="{{ route('profile.edit') }}" class="stretched-link" aria-label="Manage Account Settings"></a>
        </div>
    </div>

</div>

@endsection