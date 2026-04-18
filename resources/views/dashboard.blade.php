@extends('layouts.app')

@section('content')
    <div class="mb-4">
        <h2>Welcome {{ Auth::user()->name ?? 'Staff Member' }}</h2>
        <p class="text-muted">Manage your daily attendance and view your records.</p>
    </div>

    <div class="row g-4 mt-2">
        {{-- My Attendance Card (Cyan Gradient + Zigzag Pattern) --}}
        <div class="col-md-6 col-xl-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 position-relative text-white overflow-hidden" 
                 style="background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%); transition: transform 0.2s;"
                 onmouseover="this.style.transform='translateY(-5px)'" 
                 onmouseout="this.style.transform='translateY(0)'">
                
                <div class="position-absolute top-0 start-0 w-100 h-100" style="background-image: linear-gradient(135deg, rgba(255,255,255,0.1) 25%, transparent 25%), linear-gradient(225deg, rgba(255,255,255,0.1) 25%, transparent 25%), linear-gradient(45deg, rgba(255,255,255,0.1) 25%, transparent 25%), linear-gradient(315deg, rgba(255,255,255,0.1) 25%, transparent 25%); background-position: 10px 0, 10px 0, 0 0, 0 0; background-size: 20px 20px; background-repeat: repeat; pointer-events: none;"></div>

                <div class="card-body p-4 d-flex justify-content-between align-items-center position-relative" style="z-index: 1;">
                    <div>
                        <p class="text-white-50 mb-1 fw-bold text-uppercase" style="letter-spacing: 1px;">Daily Log</p>
                        <h2 class="mb-0 fw-bold fs-3 mt-2">My Attendance <i class="bi bi-arrow-right ms-1"></i></h2>
                    </div>
                    <div class="bg-white bg-opacity-25 rounded-circle p-3 d-flex align-items-center justify-content-center shadow-sm" style="width: 70px; height: 70px;">
                        <i class="bi bi-calendar-check-fill fs-2 text-white"></i>
                    </div>
                    
                    {{-- Stretched Link to Attendance --}}
                    <a href="{{ url('/attendance') }}" class="stretched-link"></a>
                </div>
            </div>
        </div>

        {{-- My Profile Card (Rose Gradient + Vertical Lines Pattern) --}}
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
                    <div class="bg-white bg-opacity-25 rounded-circle p-3 d-flex align-items-center justify-content-center shadow-sm" style="width: 70px; height: 70px;">
                        <i class="bi bi-person-badge-fill fs-2 text-white"></i>
                    </div>
                    
                    {{-- Stretched Link to Profile --}}
                    <a href="{{ route('profile.edit') }}" class="stretched-link"></a>
                </div>
            </div>
        </div>
    </div>
@endsection