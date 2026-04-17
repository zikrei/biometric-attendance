@extends('layouts.app')

@section('title', 'Dashboard')

@section('page_title', 'Welcome to the Dashboard')

@section('page_subtitle', 'Your personalized dashboard based on your role.')

@section('content')
    {{-- User Dashboard --}}
    @if(auth()->user()->role === 'user')
        <div class="row g-4">
            <div class="col-md-6 col-xl-3">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1">Your Attendance</p>
                                <h3 class="mb-0">95%</h3>
                            </div>
                            <div class="fs-2 text-info">
                                <i class="bi bi-person-check-fill"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1">Pending Discrepancies</p>
                                <h3 class="mb-0">1</h3>
                            </div>
                            <div class="fs-2 text-warning">
                                <i class="bi bi-exclamation-circle-fill"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Widgets for User -->
        </div>
    {{-- HOD Dashboard --}}
    @elseif(auth()->user()->role === 'hod')
        <div class="row g-4">
            <div class="col-md-6 col-xl-3">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1">Staff Attendance</p>
                                <h3 class="mb-0">12</h3>
                            </div>
                            <div class="fs-2 text-success">
                                <i class="bi bi-check-circle-fill"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1">Pending Approvals</p>
                                <h3 class="mb-0">3</h3>
                            </div>
                            <div class="fs-2 text-danger">
                                <i class="bi bi-hourglass-split"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Widgets for HOD -->
        </div>
    {{-- Admin Dashboard --}}
    @elseif(auth()->user()->role === 'admin')
        <div class="row g-4">
            <div class="col-md-6 col-xl-3">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1">Total Users</p>
                                <h3 class="mb-0">150</h3>
                            </div>
                            <div class="fs-2 text-primary">
                                <i class="bi bi-person-fill"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1">Attendance Issues</p>
                                <h3 class="mb-0">5</h3>
                            </div>
                            <div class="fs-2 text-warning">
                                <i class="bi bi-exclamation-circle-fill"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Widgets for Admin -->
        </div>
    {{-- Integrity Unit Dashboard --}}
    @elseif(auth()->user()->role === 'integrity')
        <div class="row g-4">
            <div class="col-md-6 col-xl-3">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1">Verified Reports</p>
                                <h3 class="mb-0">50</h3>
                            </div>
                            <div class="fs-2 text-success">
                                <i class="bi bi-check-circle-fill"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1">Pending Reviews</p>
                                <h3 class="mb-0">2</h3>
                            </div>
                            <div class="fs-2 text-primary">
                                <i class="bi bi-folder-lock"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Widgets for Integrity Unit -->
        </div>
    @endif
@endsection