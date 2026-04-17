@extends('layouts.app')

@section('title', 'User Dashboard')

@section('page_title', 'Welcome User')

@section('page_subtitle', 'Track your attendance and submit discrepancies.')

@section('content')
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

        <!-- Add more widgets specific to User here -->
    </div>
@endsection