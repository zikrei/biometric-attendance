@extends('layouts.app')

@section('content')
{{-- 
  PHASE 1: LAYOUT & VIEWPORT POSITIONING
  OBJECTIVE: Center the reset module within the viewport to maintain a focused user experience.
  PROCEDURE: Utilizes the 'auth-page-wrapper' and 'd-flex' alignment classes defined in the global design system.
--}}
<div class="container d-flex justify-content-center align-items-center auth-page-wrapper">
    <div class="col-12 col-md-6 col-lg-5">
        <div class="card shadow-lg border-0 rounded-4 p-4 p-md-5">
            
            {{-- 
              PHASE 2: BRANDING & CONTEXTUAL HEADER
              OBJECTIVE: Reassure the user of the system's security while providing clear instructions.
              COMPONENTS: 
              - 'auth-icon-circle': Stylized circular container for the security icon.
              - 'text-purple-primary': Aligns the header with the organization's color scheme.
            --}}
            <div class="text-center mb-4">
                <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3 auth-icon-circle">
                    <i class="bi bi-shield-lock-fill fs-2 text-purple-primary"></i>
                </div>
                <h3 class="fw-bold text-purple-primary">Set New Password</h3>
                <p class="text-muted">Enter your new password below to complete the password reset process.</p>
            </div>

            <form method="POST" action="{{ route('password.update') }}">
                {{-- 
                  PHASE 3: TRANSACTIONAL SECURITY & TOKEN HANDLING
                  OBJECTIVE: Ensure the authenticity of the reset request via cross-site and token validation.
                  SECURITY: 
                  - Includes @csrf to prevent request forgery.
                  - Passes the hidden reset 'token' to verify the email link's validity.
                --}}
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                {{-- 
                  PHASE 4: IDENTITY & CREDENTIAL INPUT
                  OBJECTIVE: Capture and validate the new user credentials.
                  PROCEDURE: 
                  - Email is set to 'readonly' to preserve the identity verified by the token.
                  - Password confirmation field is included to prevent clerical entry errors.
                --}}
                <div class="mb-3">
                    <label class="fw-semibold text-secondary mb-1">Email Address</label>
                    <input type="email" name="email" class="form-control form-control-lg bg-light border-0" value="{{ request()->email }}" required readonly>
                </div>

                <div class="mb-3">
                    <label class="fw-semibold text-secondary mb-1">New Password</label>
                    <input type="password" name="password" class="form-control form-control-lg bg-light border-0 @error('password') is-invalid @enderror" required>
                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-4">
                    <label class="fw-semibold text-secondary mb-1">Confirm New Password</label>
                    <input type="password" name="password_confirmation" class="form-control form-control-lg bg-light border-0" required>
                </div>

                {{-- 
                  PHASE 5: ACTION PERSISTENCE
                  OBJECTIVE: Finalize the password update and commit the change to the database.
                  STYLING: Employs the 'btn-purple-gradient' to indicate a primary, finalized action.
                --}}
                <div class="text-center">
                    <button type="submit" class="btn w-100 fw-bold rounded-3 py-2 btn-purple-gradient">
                        Update Password
                    </button>
                </div>
            </form>
            
        </div>
    </div>
</div>
@endsection