@extends('layouts.app')

@section('content')
{{-- The d-flex and min-height perfectly center the box on the screen --}}
<div class="container d-flex justify-content-center align-items-center" style="min-height: 75vh;">
    <div class="col-12 col-md-6 col-lg-5">
        <div class="card shadow-lg border-0 rounded-4 p-4 p-md-5">
            
            {{-- Centered Purple Header & Icon --}}
            <div class="text-center mb-4">
                <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px; background: rgba(139, 92, 246, 0.1);">
                    <i class="bi bi-key-fill fs-2" style="color: #6d28d9;"></i>
                </div>
                <h3 class="fw-bold" style="color: #6d28d9;">Reset Password</h3>
                <p class="text-muted">Enter your email address to receive a password reset link.</p>
            </div>

            @if(session('success'))
                <div class="alert alert-success border-0 bg-success bg-opacity-10 text-success fw-bold text-center rounded-3">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                <div class="mb-4">
                    <label class="fw-semibold text-secondary mb-1">Email Address</label>
                    <input type="email" name="email" class="form-control form-control-lg bg-light border-0 @error('email') is-invalid @enderror" required autofocus>
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                
                {{-- Centered Purple Button --}}
                <div class="text-center">
                    <button type="submit" class="btn text-white w-100 fw-bold rounded-3 py-2" 
                            style="background: linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%); border: none; transition: transform 0.2s;" 
                            onmouseover="this.style.transform='translateY(-2px)'" 
                            onmouseout="this.style.transform='translateY(0)'">
                        Send Reset Link
                    </button>
                </div>
                
                <div class="text-center mt-4">
                    <a href="{{ route('login') }}" class="text-decoration-none fw-semibold" style="color: #8b5cf6;">
                        <i class="bi bi-arrow-left me-1"></i> Back to Login
                    </a>
                </div>
            </form>
            
        </div>
    </div>
</div>
@endsection