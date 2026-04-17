@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('page_title', 'Welcome Admin')

@section('page_subtitle', 'Manage users, oversee the system, and view reports.')

@section('content')
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

        <!-- Add more widgets specific to Admin here -->
    </div>
@endsection