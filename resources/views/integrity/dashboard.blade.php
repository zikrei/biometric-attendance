@extends('layouts.app')

@section('title', 'Integrity Dashboard')

{{-- 
  PHASE 1: WORKFLOW CONTEXT & HEADER
--}}
@section('page_title')
    Welcome, {{ Auth::user()->name ?? 'Integrity User' }}
@endsection
@section('page_subtitle', 'Monitor HOD attendance records and system-wide discrepancies.')

@section('content')
<div class="row g-4 mt-1">
    
    {{-- PHASE 2: INTEGRITY APPROVALS CARD --}}
    <div class="col-md-6 col-xl-4">
        <div class="dashboard-card card-hod-approvals pattern-grid h-100" style="min-height: 150px;">
            <div class="card-content">
                <div>
                    <p class="card-label">HOD APPROVAL REQUESTS</p>
                    <h2 class="card-number">{{ $pendingApprovals ?? 0 }}</h2>
                    <small class="card-desc">Pending attendance and discrepancy requests</small>
                </div>
                <div class="card-icon">
                    <i class="bi bi-shield-check"></i>
                </div>
            </div>
            <a href="{{ route('integrity.approvals') }}" class="stretched-link" aria-label="View Pending Approval Requests"></a>
        </div>
    </div>

    {{-- PHASE 3: REPORTING CARD --}}
    <div class="col-md-6 col-xl-4">
        <div class="dashboard-card card-admin-reports h-100" style="min-height: 150px;">
            <div class="card-content">
                <div>
                    <p class="card-label">REPORTING</p>
                    <h2 class="card-title mb-2">Reports</h2>
                    <small class="card-desc">Generate and review system-wide attendance reports</small>
                </div>
                <div class="card-icon">
                    <i class="bi bi-file-earmark-bar-graph"></i>
                </div>
            </div>
            <a href="{{ route('reports.index') }}" class="stretched-link" aria-label="View Reports"></a>
        </div>
    </div>

    {{-- PHASE 4: ACCOUNT SETTINGS CARD --}}
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