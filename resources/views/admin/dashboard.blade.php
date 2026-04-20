@extends('layouts.app')

@section('title', 'Admin Dashboard')
@section('page_title', 'Admin Dashboard')
@section('page_subtitle', 'Manage users, monitor system activity, and generate reports.')

@section('content')

<div class="dashboard-welcome mb-4">
    <h3 class="fw-bold">Welcome, {{ $user->name }}</h3>
    <p class="text-muted mb-0">
        Monitor system performance and manage key administrative functions.
    </p>
</div>

<div class="row g-4">

    {{-- Total Users --}}
    <div class="col-md-6 col-xl-4">
        <div class="dashboard-card card-admin-users">

            <div class="card-content">
                <div>
                    <p class="card-label">Users</p>
                    <h2 class="card-number">{{ $totalUsers }}</h2>
                    <small class="card-desc">Total registered users</small>
                </div>

                <div class="card-icon">
                    <i class="bi bi-people-fill"></i>
                </div>
            </div>

            <a href="{{ url('/admin/users') }}" class="stretched-link"></a>
        </div>
    </div>

    {{-- Reports --}}
    <div class="col-md-6 col-xl-4">
        <div class="dashboard-card card-admin-reports">

            <div class="card-content">
                <div>
                    <p class="card-label">Reports</p>
                    <h4 class="card-title">Generate Reports</h4>
                    <small class="card-desc">View and export attendance reports</small>
                </div>

                <div class="card-icon">
                    <i class="bi bi-file-earmark-text-fill"></i>
                </div>
            </div>

            <a href="{{ url('/admin/reports') }}" class="stretched-link"></a>
        </div>
    </div>

    {{-- Profile --}}
    <div class="col-md-6 col-xl-4">
        <div class="dashboard-card card-admin-profile">

            <div class="card-content">
                <div>
                    <p class="card-label">Account</p>
                    <h4 class="card-title">Manage Profile</h4>
                    <small class="card-desc">Update your personal information</small>
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