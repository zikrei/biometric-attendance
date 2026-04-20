@extends('layouts.app')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')
@section('page_subtitle', 'Access your attendance records and manage your profile.')

@section('content')

<div class="dashboard-welcome mb-4">
    <h3 class="fw-bold">Welcome, {{ Auth::user()->name ?? 'User' }}</h3>
    <p class="text-muted mb-0">
        Manage your attendance records and account information from this dashboard.
    </p>
</div>

<div class="row g-4">

    {{-- Attendance Card --}}
    <div class="col-md-6 col-xl-4">
        <div class="dashboard-card card-attendance">

            <div class="card-content">
                <div>
                    <p class="card-label">Attendance</p>
                    <h4 class="card-title">View Attendance Records</h4>
                </div>

                <div class="card-icon">
                    <i class="bi bi-calendar-check-fill"></i>
                </div>
            </div>

            <a href="{{ url('/attendance') }}" class="stretched-link"></a>
        </div>
    </div>

    {{-- Profile Card --}}
    <div class="col-md-6 col-xl-4">
        <div class="dashboard-card card-profile">

            <div class="card-content">
                <div>
                    <p class="card-label">Account</p>
                    <h4 class="card-title">Manage Profile</h4>
                </div>

                <div class="card-icon">
                    <i class="bi bi-person-badge-fill"></i>
                </div>
            </div>

            <a href="{{ route('profile.edit') }}" class="stretched-link"></a>
        </div>
    </div>

</div>

@endsection