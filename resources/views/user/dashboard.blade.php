@extends('layouts.app')

@section('content')
    <div class="mb-4">
        {{-- Dynamically print the user's name --}}
        <h2>Welcome {{ $user->name }}</h2>
        <p class="text-muted">Track your attendance and submit discrepancies.</p>
    </div>

    <div class="row g-4">
        {{-- Attendance Percentage Card --}}
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Your Attendance (This Month)</p>
                        {{-- Dynamically print the percentage --}}
                        <h2 class="mb-0">{{ $attendancePercentage }}%</h2>
                    </div>
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="bi bi-person-check fs-3"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Pending Discrepancies Card --}}
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Pending Discrepancies</p>
                        {{-- Dynamically print the count --}}
                        <h2 class="mb-0">{{ $pendingDiscrepancies }}</h2>
                    </div>
                    <div class="bg-warning bg-opacity-10 text-warning rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="bi bi-exclamation-circle fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection