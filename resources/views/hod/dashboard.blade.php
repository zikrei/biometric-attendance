@extends('layouts.app')

@section('content')
    <style>
        .hover-card {
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
            cursor: pointer;
        }
        .hover-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 .5rem 1rem rgba(0,0,0,.15) !important;
        }
    </style>

    <div class="mb-4">
        <h2>Welcome {{ $user->name }}</h2>
        <p class="text-muted">Oversee staff attendance and approve discrepancies.</p>
    </div>

    <div class="row g-4">
        {{-- Pending Approvals Card ONLY --}}
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100 hover-card position-relative">
                <div class="card-body p-4 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Pending Approvals</p>
                        <h2 class="mb-0">{{ $pendingApprovals }}</h2>
                    </div>
                    <div class="bg-danger bg-opacity-10 text-danger rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="bi bi-hourglass-split fs-3"></i>
                    </div>
                    
                    {{-- The Stretched Link! --}}
                    <a href="{{ route('hod.approvals') }}" class="stretched-link"></a>
                </div>
            </div>
        </div>
    </div>
@endsection