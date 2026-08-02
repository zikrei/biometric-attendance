@extends('layouts.app')

@section('title', 'Staff Dashboard')

{{-- 
  PHASE 1: WORKFLOW CONTEXT & HEADER
--}}
@section('page_title')
    Welcome, {{ Auth::user()->name ?? 'IT Staff' }}
@endsection
@section('page_subtitle', 'View your attendance records and manage your profile.')

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
            <a href="{{ route('attendance.list') }}" class="stretched-link" aria-label="View Attendance Records"></a>
        </div>
    </div>

    {{-- PHASE 3: ACCOUNT SETTINGS CARD --}}
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