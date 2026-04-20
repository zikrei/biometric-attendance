@extends('layouts.app')

@section('title', 'Integrity Overview')

{{-- Push the Welcome message directly into the Top Header --}}
@section('page_title')
    Welcome back, {{ $user->name ?? 'Integrity Officer' }} 👋
@endsection

@section('page_subtitle', 'Oversee HOD attendance and system-wide discrepancies.')

@section('content')

<div class="row g-4 mt-1">

    {{-- 1. Integrity Pending Approvals Card (Purple Gradient) --}}
    <div class="col-md-6 col-xl-4">
        <div class="dashboard-card card-integrity-approvals pattern-diagonal-reverse h-100">
            <div class="card-content">
                <div>
                    <p class="card-label">HOD Requests</p>
                    <h2 class="card-number">{{ $pendingApprovals ?? 0 }}</h2>
                    <small class="card-desc">Pending attendance & discrepancy approvals</small>
                </div>
                <div class="card-icon">
                    <i class="bi bi-shield-check"></i>
                </div>
            </div>
            <a href="{{ route('integrity.approvals') }}" class="stretched-link" aria-label="Review Pending Approvals"></a>
        </div>
    </div>

    {{-- 2. System Reports Card (Green Gradient) --}}
    <div class="col-md-6 col-xl-4">
        <div class="dashboard-card card-admin-reports pattern-diagonal h-100">
            <div class="card-content">
                <div>
                    <p class="card-label">Analytics</p>
                    <h4 class="card-title">System Reports</h4>
                    <small class="card-desc">Generate and view system-wide logs</small>
                </div>
                <div class="card-icon">
                    <i class="bi bi-file-earmark-bar-graph"></i>
                </div>
            </div>
            <a href="{{ url('/admin/reports') }}" class="stretched-link" aria-label="Generate System Reports"></a>
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