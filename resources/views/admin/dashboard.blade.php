@extends('layouts.app')

@section('title', 'Admin Dashboard')
@section('page_title')
    Welcome, {{ $user->name ?? 'System Administrator' }}
@endsection
@section('page_subtitle', 'Overview of system activity and key metrics for the biometric attendance system.')

@section('content')
<div class="row g-4 mt-1">

    {{-- Active Users Metric Card --}}
    <div class="col-md-6 col-xl-4">
        <div class="dashboard-card card-admin-users h-100">
            <div class="card-content">
                <div>
                    <p class="card-label">User Statistics</p>
                    <h2 class="card-number">{{ $totalUsers }}</h2>
                    <small class="card-desc">Total active user accounts</small>
                </div>
                <div class="card-icon">
                    <i class="bi bi-people"></i>
                </div>
            </div>
            <a href="{{ url('/admin/users') }}" class="stretched-link" aria-label="View User Management"></a>
        </div>
    </div>

    {{-- Reports Action Card --}}
    <div class="col-md-6 col-xl-4">
        <div class="dashboard-card card-admin-reports h-100">
            <div class="card-content">
                <div>
                    <p class="card-label">Reporting</p>
                    <h4 class="card-title">Reports</h4>
                    <small class="card-desc">View and export attendance records</small>
                </div>
                <div class="card-icon">
                    <i class="bi bi-file-earmark-bar-graph"></i>
                </div>
            </div>
            <a href="{{ url('/admin/reports') }}" class="stretched-link" aria-label="Access Reports"></a>
        </div>
    </div>

    {{-- Profile Action Card --}}
    <div class="col-md-6 col-xl-4">
        <div class="dashboard-card card-admin-profile h-100">
            <div class="card-content">
                <div>
                    <p class="card-label">Account</p>
                    <h4 class="card-title">Profile Settings</h4>
                    <small class="card-desc">Manage your account information and credentials</small>
                </div>
                <div class="card-icon">
                    <i class="bi bi-shield-lock"></i>
                </div>
            </div>
            <a href="{{ route('profile.edit') }}" class="stretched-link" aria-label="Manage Profile Settings"></a>
        </div>
    </div>

</div>

@endsection