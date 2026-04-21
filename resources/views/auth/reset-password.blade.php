@extends('layouts.app')

@section('content')
{{-- Using our custom auth-page-wrapper to perfectly center the box --}}
<div class="container d-flex justify-content-center align-items-center auth-page-wrapper">
    <div class="col-12 col-md-6 col-lg-5">
        <div class="card shadow-lg border-0 rounded-4 p-4 p-md-5">
            
            {{-- Centered Purple Header & Icon --}}
            <div class="text-center mb-4">
                <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3 auth-icon-circle">
                    <i class="bi bi-shield-lock-fill fs-2 text-purple-primary"></i>
                </div>
                <h3 class="fw-bold text-purple-primary">Set New Password</h3>
                <p class="text-muted">Enter your new password below to complete the password reset process.</p>
            </div>

            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

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

                {{-- Centered Purple Button utilizing our global CSS --}}
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