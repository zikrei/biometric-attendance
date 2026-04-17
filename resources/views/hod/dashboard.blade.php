@extends('layouts.app')

@section('title', 'HOD Dashboard')

@section('page_title', 'Welcome Head of Department')

@section('page_subtitle', 'Oversee staff attendance and approve discrepancies.')

@section('content')
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

        <!-- Add more widgets specific to HOD here -->
    </div>
@endsection