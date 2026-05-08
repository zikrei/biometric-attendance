@extends('layouts.app')

@section('title', 'Admin Dashboard')

{{-- 
  PHASE 1: DASHBOARD CONTEXT & PERSONALIZATION
  OBJECTIVE: Dynamically greet the authenticated administrator and establish the system's operational purpose.
  PROCEDURE: Pulls the user's name attribute with a fallback for system consistency.
--}}
@section('page_title')
    Welcome, {{ $user->name ?? 'System Administrator' }}
@endsection
@section('page_subtitle', 'Overview of system activity and key metrics for the biometric attendance system.')

@section('content')
<div class="row g-4 mt-1">

    {{-- 
      PHASE 2: METRIC VISUALIZATION (USER STATISTICS)
      OBJECTIVE: Provide an immediate count of active employee records for quick-glance auditing.
      STYLING: Implements the 'card-admin-users' theme from app.css for visual distinction.
    --}}
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

    {{-- 
      PHASE 3: ADMINISTRATIVE NAVIGATION (REPORTING)
      OBJECTIVE: Create a high-visibility entry point for attendance record extraction and audit generation.
      INTERACTION: Utilizes 'stretched-link' to ensure the entire card surface is actionable.
    --}}
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

    {{-- 
      PHASE 4: SECURITY & PERSONAL ACCOUNT MANAGEMENT
      OBJECTIVE: Securely route the user to their individual profile and credential settings.
      PROCEDURE: Maps to the 'profile.edit' route established in Phase 2 of the routing system.
    --}}
    <div class="col-md-6 col-xl-4">
        <div class="dashboard-card card-profile h-100">
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