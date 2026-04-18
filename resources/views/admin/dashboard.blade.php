@extends('layouts.app')

@section('content')
    <style>
        .hover-card {
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
            cursor: pointer;
            overflow: hidden; /* Keeps the pattern inside the rounded corners */
        }
        .hover-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 .75rem 1.5rem rgba(0,0,0,.15) !important;
        }
        
        /* COLOR GRADIENTS */
        .bg-gradient-primary {
            background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
        }
        .bg-gradient-success {
            background: linear-gradient(135deg, #0d9488 0%, #10b981 100%);
        }

        /* GEOMETRIC PATTERNS */
        .pattern-dots {
            background-image: radial-gradient(rgba(255, 255, 255, 0.15) 2px, transparent 2px);
            background-size: 20px 20px;
        }
        .pattern-diagonal {
            background-image: repeating-linear-gradient(45deg, transparent, transparent 10px, rgba(255, 255, 255, 0.1) 10px, rgba(255, 255, 255, 0.1) 20px);
        }
    </style>

    <div class="mb-4">
        <h2>Welcome {{ $user->name }}</h2>
        <p class="text-muted">Manage users, oversee the system, and view reports.</p>
    </div>

<div class="row g-4 mt-2">
    {{-- Total Users Card (Blue) --}}
    <div class="col-md-6 col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 h-100 position-relative text-white overflow-hidden" 
             style="background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%); transition: transform 0.2s;"
             onmouseover="this.style.transform='translateY(-5px)'" 
             onmouseout="this.style.transform='translateY(0)'">
            
            <div class="position-absolute top-0 start-0 w-100 h-100" style="background-image: radial-gradient(rgba(255, 255, 255, 0.15) 2px, transparent 2px); background-size: 20px 20px; pointer-events: none;"></div>

            <div class="card-body p-4 d-flex justify-content-between align-items-center position-relative" style="z-index: 1;">
                <div>
                    <p class="text-white-50 mb-1 fw-bold text-uppercase" style="letter-spacing: 1px;">Total Users</p>
                    <h2 class="mb-0 fw-bold display-5">{{ $totalUsers }}</h2>
                </div>
                <div class="bg-white bg-opacity-25 rounded-circle p-3 d-flex align-items-center justify-content-center shadow-sm" style="width: 90px; height: 90px;">
                    <i class="bi bi-people-fill fs-1 text-white"></i>
                </div>
                
                <a href="{{ url('/admin/users') }}" class="stretched-link"></a>
            </div>
        </div>
    </div>

    {{-- Reports Shortcut Card (Green) --}}
    <div class="col-md-6 col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 h-100 position-relative text-white overflow-hidden" 
             style="background: linear-gradient(135deg, #0d9488 0%, #10b981 100%); transition: transform 0.2s;"
             onmouseover="this.style.transform='translateY(-5px)'" 
             onmouseout="this.style.transform='translateY(0)'">
            
            <div class="position-absolute top-0 start-0 w-100 h-100" style="background-image: repeating-linear-gradient(45deg, transparent, transparent 10px, rgba(255, 255, 255, 0.1) 10px, rgba(255, 255, 255, 0.1) 20px); pointer-events: none;"></div>

            <div class="card-body p-4 d-flex justify-content-between align-items-center position-relative" style="z-index: 1;">
                <div>
                    <p class="text-white-50 mb-1 fw-bold text-uppercase" style="letter-spacing: 1px;">System Reports</p>
                    <h2 class="mb-0 fw-bold fs-3 mt-2">Generate <i class="bi bi-arrow-right ms-1"></i></h2>
                </div>
                <div class="bg-white bg-opacity-25 rounded-circle p-3 d-flex align-items-center justify-content-center shadow-sm" style="width: 90px; height: 90px;">
                    <i class="bi bi-file-earmark-text-fill fs-1 text-white"></i>
                </div>
                
                <a href="{{ url('/admin/reports') }}" class="stretched-link"></a>
            </div>
        </div>
    </div>

    {{-- My Profile Card (Pink) --}}
    <div class="col-md-6 col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 h-100 position-relative text-white overflow-hidden" 
             style="background: linear-gradient(135deg, #f43f5e 0%, #e11d48 100%); transition: transform 0.2s;"
             onmouseover="this.style.transform='translateY(-5px)'" 
             onmouseout="this.style.transform='translateY(0)'">
            
            <div class="position-absolute top-0 start-0 w-100 h-100" style="background-image: repeating-linear-gradient(90deg, transparent, transparent 10px, rgba(255, 255, 255, 0.1) 10px, rgba(255, 255, 255, 0.1) 20px); pointer-events: none;"></div>

            <div class="card-body p-4 d-flex justify-content-between align-items-center position-relative" style="z-index: 1;">
                <div>
                    <p class="text-white-50 mb-1 fw-bold text-uppercase" style="letter-spacing: 1px;">Account</p>
                    <h2 class="mb-0 fw-bold fs-3 mt-2">My Profile <i class="bi bi-arrow-right ms-1"></i></h2>
                </div>
                <div class="bg-white bg-opacity-25 rounded-circle p-3 d-flex align-items-center justify-content-center shadow-sm" style="width: 90px; height: 90px;">
                    <i class="bi bi-person-badge-fill fs-1 text-white"></i>
                </div>
                
                <a href="{{ route('profile.edit') }}" class="stretched-link"></a>
            </div>
        </div>
    </div>
</div>
@endsection