@extends('layouts.app')

@section('title', 'Admin Dashboard')

{{-- Push the Welcome message directly into the Top Header --}}
@section('page_title')
    Welcome back, {{ $user->name ?? 'System Admin' }} 👋
@endsection

@section('page_subtitle', 'Here is what\'s happening with your biometric attendance system today.')

@section('content')

<div class="row g-4 mt-1">

    {{-- Active Users Metric Card --}}
    <div class="col-md-6 col-xl-4">
        <div class="dashboard-card card-admin-users h-100">
            <div class="card-content">
                <div>
                    <p class="card-label">System Data</p>
                    <h2 class="card-number">{{ $totalUsers }}</h2>
                    <small class="card-desc">Total registered active users</small>
                </div>
                <div class="card-icon">
                    <i class="bi bi-people"></i>
                </div>
            </div>
            <a href="{{ url('/admin/users') }}" class="stretched-link" aria-label="View User Directory"></a>
        </div>
    </div>

    {{-- Reports Action Card --}}
    <div class="col-md-6 col-xl-4">
        <div class="dashboard-card card-admin-reports h-100">
            <div class="card-content">
                <div>
                    <p class="card-label">Analytics</p>
                    <h4 class="card-title">System Reports</h4>
                    <small class="card-desc">Export and view attendance logs</small>
                </div>
                <div class="card-icon">
                    <i class="bi bi-file-earmark-bar-graph"></i>
                </div>
            </div>
            <a href="{{ url('/admin/reports') }}" class="stretched-link" aria-label="Generate System Reports"></a>
        </div>
    </div>

    {{-- Profile Action Card --}}
    <div class="col-md-6 col-xl-4">
        <div class="dashboard-card card-admin-profile h-100">
            <div class="card-content">
                <div>
                    <p class="card-label">Configuration</p>
                    <h4 class="card-title">Account Settings</h4>
                    <small class="card-desc">Update your credentials and profile</small>
                </div>
                <div class="card-icon">
                    <i class="bi bi-shield-lock"></i>
                </div>
            </div>
            <a href="{{ route('profile.edit') }}" class="stretched-link" aria-label="Manage Account Settings"></a>
        </div>
    </div>

</div>

@endsection