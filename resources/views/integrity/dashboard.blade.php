@extends('layouts.app')

@section('content')
    <div class="mb-4">
        <h2>Welcome {{ $user->name ?? 'Integrity Officer' }}</h2>
        <p class="text-muted">Oversee HOD attendance and system-wide discrepancies.</p>
    </div>

    {{-- The row wrapper is what keeps them side-by-side! --}}
    <div class="row g-4 mt-2">
        
        {{-- 1. Integrity Pending Approvals Card (Purple Gradient + Reverse Diagonal Pattern) --}}
        <div class="col-md-6 col-xl-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 position-relative text-white overflow-hidden" 
                 style="background: linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%); transition: transform 0.2s;"
                 onmouseover="this.style.transform='translateY(-5px)'" 
                 onmouseout="this.style.transform='translateY(0)'">
                
                <div class="position-absolute top-0 start-0 w-100 h-100" style="background-image: repeating-linear-gradient(-45deg, transparent, transparent 10px, rgba(255, 255, 255, 0.1) 10px, rgba(255, 255, 255, 0.1) 20px); pointer-events: none;"></div>

                <div class="card-body p-4 d-flex justify-content-between align-items-center position-relative" style="z-index: 1;">
                    <div>
                        <p class="text-white-50 mb-1 fw-bold text-uppercase" style="letter-spacing: 1px;">HOD Requests</p>
                        <h2 class="mb-0 fw-bold display-5">{{ $pendingApprovals ?? 0 }}</h2>
                    </div>
                    <div class="bg-white bg-opacity-25 rounded-circle p-3 d-flex align-items-center justify-content-center shadow-sm" style="width: 90px; height: 90px;">
                        <i class="bi bi-shield-check fs-1 text-white"></i>
                    </div>
                    
                    <a href="{{ route('integrity.approvals') }}" class="stretched-link"></a>
                </div>
            </div>
        </div>

        {{-- 2. System Reports Card (Green Gradient + Diagonal Lines) --}}
        <div class="col-md-6 col-xl-4">
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

        {{-- 3. My Profile Card (Rose Gradient + Vertical Lines Pattern) --}}
        <div class="col-md-6 col-xl-4">
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