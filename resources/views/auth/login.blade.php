<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In | Biometric Attendance Management System</title>

    {{-- Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    {{-- Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="login-page">

<div class="login-wrapper">
    <div class="login-container row g-0">

        {{-- LEFT PANEL --}}
        <div class="col-lg-6">
            <div class="login-left">

                <span class="system-badge">
                    <i class="bi bi-shield-lock me-1"></i> Secure Access Portal
                </span>

                <h1>Biometric Attendance Management System</h1>

                <p>
                    A secure platform for attendance tracking, approval workflows,
                    and centralized reporting.
                </p>

                <div class="feature-list">

                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="bi bi-fingerprint"></i>
                        </div>
                        <div>
                            <h6 class="mb-1">Biometric Synchronization</h6>
                            <small class="text-white-50">
                                Real-time synchronization with biometric devices.
                            </small>
                        </div>
                    </div>

                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="bi bi-person-bounding-box"></i>
                        </div>
                        <div>
                            <h6 class="mb-1">Role-Based Access Control</h6>
                            <small class="text-white-50">
                                Secure dashboards for Staff, Heads of Department, Administrators, and the Integrity Unit.
                            </small>
                        </div>
                    </div>

                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="bi bi-clipboard-data"></i>
                        </div>
                        <div>
                            <h6 class="mb-1">Reporting and Analytics</h6>
                            <small class="text-white-50">
                                Generate attendance reports and analytical summaries efficiently.
                            </small>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- RIGHT PANEL --}}
        <div class="col-lg-6">
            <div class="login-right d-flex align-items-center h-100">

                <div class="w-100">

                    <h2 class="login-card-title">Sign In</h2>
                    <p class="login-card-subtitle">
                        Please enter your credentials to access the system securely.
                    </p>

                    {{-- Alerts --}}
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0 ps-3">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-envelope"></i>
                                </span>
                                <input type="email" name="email" class="form-control"
                                       placeholder="name@company.com" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Password</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-lock"></i>
                                </span>
                                <input type="password" name="password" class="form-control"
                                       placeholder="Enter your password" required>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mb-4">
                            <div class="d-flex justify-content-between mb-4">
                                <div class="form-check">
                                    {{-- Added id="remember" --}}
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                    
                                    {{-- Added for="remember" and cursor pointer --}}
                                    <label class="form-check-label text-muted" for="remember" style="cursor: pointer;">
                                        Keep me signed in
                                    </label>
                                </div>
                            </div>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-decoration-none text-primary fw-bold" style="font-size: 0.9rem;">
                                    Forgot your password?
                                </a>
                            @endif
                        </div>

                        <button type="submit" class="btn btn-login w-100 text-white">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Sign In to System
                        </button>
                    </form>

                    <div class="text-center mt-5">
                        <p class="login-footer-text mb-0">
                            <i class="bi bi-shield-check text-success me-1"></i>
                            Secure Access Enabled
                        </p>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>

</body>
</html>