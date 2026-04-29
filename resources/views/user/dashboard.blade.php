@extends('layouts.app')

@section('title', 'My Dashboard')

@section('page_title')
    Welcome back, {{ Auth::user()->name ?? 'Staff Member' }} 👋
@endsection

@section('page_subtitle', 'Manage your daily attendance and view your personal records.')

@section('content')

<div class="row g-4 mt-1">

    {{-- Attendance Log Card --}}
    <div class="col-md-6">
        <div class="dashboard-card card-attendance h-100">
            <div class="card-content">
                <div>
                    <p class="card-label">Daily Log</p>
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

    {{-- Account Settings Card --}}
    <div class="col-md-6">
        <div class="dashboard-card card-profile h-100">
            <div class="card-content">
                <div>
                    <p class="card-label">Account</p>
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