@extends('layouts.app')

@section('content')
{{-- 
  PHASE 1: LAYOUT & VERTICAL ALIGNMENT
  OBJECTIVE: Achieve perfect center-screen placement for the authentication module.
  PROCEDURE: Leverages the 'auth-page-wrapper' utility and d-flex alignment to maintain visual balance on all devices.
--}}
<div class="container d-flex justify-content-center align-items-center auth-page-wrapper">
    <div class="col-12 col-md-6 col-lg-5">
        <div class="card shadow-lg border-0 rounded-4 p-4 p-md-5">
            
            {{-- 
              PHASE 2: VISUAL BRANDING & CONTEXTUAL HEADER
              OBJECTIVE: Establish a high-trust environment for security sensitive operations.
              COMPONENTS: 
              - 'auth-icon-circle': Custom utility for centered, stylized icons.
              - 'text-purple-primary': Aligns with the core system color palette.
            --}}
            <div class="text-center mb-4">
                <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3 auth-icon-circle">
                    <i class="bi bi-key-fill fs-2 text-purple-primary"></i>
                </div>
                <h3 class="fw-bold text-purple-primary">Password Reset</h3>
                <p class="text-muted">Enter your registered email address to receive a password reset link.</p>
            </div>

            {{-- 
              PHASE 3: SESSION FEEDBACK MECHANISM
              OBJECTIVE: Confirm to the user that the recovery transaction has been initiated.
              STYLING: Uses low-opacity success backgrounds for a modern, non-jarring alert.
            --}}
            @if(session('success'))
                <div class="alert alert-success border-0 bg-success bg-opacity-10 text-success fw-bold text-center rounded-3">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                
                {{-- 
                  PHASE 4: DATA ENTRY & IDENTITY VALIDATION
                  OBJECTIVE: Capture the primary identifier required for account lookup.
                  PROCEDURE: Includes @error handling to provide immediate, field-specific security feedback.
                --}}
                <div class="mb-4">
                    <label class="fw-semibold text-secondary mb-1">Email Address</label>
                    <input type="email" name="email" class="form-control form-control-lg bg-light border-0 @error('email') is-invalid @enderror" required autofocus>
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                
                {{-- 
                  PHASE 5: ACTION PERSISTENCE & NAVIGATION
                  OBJECTIVE: Execute the recovery process or facilitate a return to the main entry point.
                  STYLING: Utilizes the 'btn-purple-gradient' to distinguish primary authentication actions.
                --}}
                <div class="text-center">
                    <button type="submit" class="btn w-100 fw-bold rounded-3 py-2 btn-purple-gradient">
                        Send Password Reset Link
                    </button>
                </div>
                
                <div class="text-center mt-4">
                    <a href="{{ route('login') }}" class="auth-back-link">
                        <i class="bi bi-arrow-left me-1"></i> Return to Sign In
                    </a>
                </div>
            </form>
            
        </div>
    </div>
</div>
@endsection