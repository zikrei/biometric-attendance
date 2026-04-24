@extends('layouts.app')

@section('title', 'Integrity Dashboard')
@section('page_title')
    Welcome, {{ $user->name ?? 'Integrity Officer' }}
@endsection
@section('page_subtitle', 'Monitor HOD attendance records and system-wide discrepancies.')

@section('content')
<div class="row g-4 mt-1">

    {{-- 1. Integrity Pending Approvals Card (Purple Gradient) --}}
    <div class="col-md-6 col-xl-4">
        <div class="dashboard-card card-integrity-approvals pattern-diagonal-reverse h-100">
            <div class="card-content">
                <div>
                    <p class="card-label">HOD Approval Requests</p>
                    <h2 class="card-number">{{ $totalPending ?? 0 }}</h2>
                    <small class="card-desc">Pending attendance and discrepancy requests</small>
                </div>
                <div class="card-icon">
                    <i class="bi bi-shield-check"></i>
                </div>
            </div>
            <a href="{{ route('integrity.approvals') }}" class="stretched-link" aria-label="View HOD Approval Requests"></a>
        </div>
    </div>

    {{-- 2. System Reports Card (Green Gradient) --}}
    <div class="col-md-6 col-xl-4">
        <div class="dashboard-card card-admin-reports pattern-diagonal h-100">
            <div class="card-content">
                <div>
                    <p class="card-label">Reporting</p>
                    <h4 class="card-title">Reports</h4>
                    <small class="card-desc">Generate and review system-wide attendance reports</small>
                </div>
                <div class="card-icon">
                    <i class="bi bi-file-earmark-bar-graph"></i>
                </div>
            </div>
            <a href="{{ url('/admin/reports') }}" class="stretched-link" aria-label="Access Reports"></a>
        </div>
    </div>

    {{-- 3. Account Settings Card (Rose Gradient) --}}
    <div class="col-md-6 col-xl-4">
        <div class="dashboard-card card-profile pattern-lines h-100">
            <div class="card-content">
                <div>
                    <p class="card-label">Account</p>
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