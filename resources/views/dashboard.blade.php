@extends('layouts.app')

@section('title', 'My Dashboard')

@section('page_title')
    Welcome back, {{ Auth::user()->name ?? 'User' }} 👋
@endsection

@section('page_subtitle', 'Access your personal attendance records and manage your profile.')

@section('content')

<div class="row g-4 mt-1">

    {{-- Attendance Card --}}
    <div class="col-md-6">
        <div class="dashboard-card card-attendance h-100">
            <div class="card-content">
                <div>
                    <p class="card-label">My Data</p>
                    <h4 class="card-title">Attendance Log</h4>
                    <small class="card-desc">Review your daily check-ins and statuses</small>
                </div>
                <div class="card-icon">
                    <i class="bi bi-calendar-check"></i>
                </div>
            </div>
            <a href="{{ url('/attendance') }}" class="stretched-link" aria-label="View My Attendance Log"></a>
        </div>
    </div>

    {{-- Profile Action Card --}}
    <div class="col-md-6">
        <div class="dashboard-card card-profile h-100">
            <div class="card-content">
                <div>
                    <p class="card-label">Configuration</p>
                    <h4 class="card-title">Account Settings</h4>
                    <small class="card-desc">Update your personal details and credentials</small>
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