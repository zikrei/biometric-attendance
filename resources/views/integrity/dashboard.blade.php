@extends('layouts.app')

@section('title', 'Integrity Unit Dashboard')

@section('page_title', 'Welcome Integrity Unit')

@section('page_subtitle', 'Review and validate reports for compliance.')

@section('content')
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

        <!-- Add more widgets specific to Integrity Unit here -->
    </div>
@endsection