<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Login | Biometric Attendance System</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="login-page" style="background: linear-gradient(rgba(46, 16, 101, 0.20), rgba(76, 29, 149, 0.20)), url('{{ asset('images/login_bg.jpg') }}'); background-size: cover; background-position: center; background-attachment: fixed; background-repeat: no-repeat;">
    <div class="login-wrapper">
        <div class="login-container row g-0">
            <div class="col-lg-6">
                <div class="login-left">
                    <span class="system-badge">
                        <i class="bi bi-shield-lock me-1"></i> Enterprise Access Portal
                    </span>

                    <h1>Biometric Attendance System</h1>
                    <p>
                        Welcome to the central workforce management platform. A secure, enterprise-grade system for tracking staff check-ins, department approvals, and system-wide integrity.
                    </p>

                    <div class="feature-list">
                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="bi bi-fingerprint"></i>
                            </div>
                            <div>
                                <h6 class="mb-1">Biometric Synchronization</h6>
                                <small class="text-white-50">Seamlessly integrates with on-site thumbprint scanners for real-time data.</small>
                            </div>
                        </div>

                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="bi bi-person-bounding-box"></i>
                            </div>
                            <div>
                                <h6 class="mb-1">Role-Based Access Control</h6>
                                <small class="text-white-50">Secure, modular dashboard environments for Staff, HODs, and Administrators.</small>
                            </div>
                        </div>

                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="bi bi-clipboard-data"></i>
                            </div>
                            <div>
                                <h6 class="mb-1">Advanced Analytics</h6>
                                <small class="text-white-50">Generate comprehensive attendance logs and discrepancy reports instantly.</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="login-right d-flex align-items-center h-100">
                    <div class="w-100">
                        <h2 class="login-card-title">Account Login</h2>
                        <p class="login-card-subtitle">
                            Enter your corporate credentials to securely access your account.
                        </p>

                        {{-- Flash Message Placeholders --}}
                        @if(session('success'))
                            <div class="alert alert-success rounded-3">{{ session('success') }}</div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger rounded-3">{{ session('error') }}</div>
                        @endif

                        @if($errors->any())
                            <div class="alert alert-danger rounded-3">
                                <ul class="mb-0 ps-3">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="#" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="email" class="form-label fw-semibold">Corporate Email</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                    <input type="email" name="email" id="email" class="form-control" placeholder="name@company.com" value="{{ old('email') }}" required>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="password" class="form-label fw-semibold">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                    <input type="password" name="password" id="password" class="form-control" placeholder="Enter your password" required>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                    <label class="form-check-label text-muted" for="remember">Keep me signed in</label>
                                </div>
                                <a href="#" class="text-decoration-none fw-medium" style="color: var(--bg-sidebar);">Forgot password?</a>
                            </div>

                            <button type="submit" class="btn btn-login w-100 text-white">
                                <i class="bi bi-box-arrow-in-right me-1"></i> Authenticate
                            </button>
                        </form>

                        <div class="text-center mt-5">
                            <p class="login-footer-text mb-0">
                                <i class="bi bi-shield-check text-success me-1"></i> Protected by enterprise-grade security.
                            </p>
                            <small class="text-muted">Authorized personnel only.</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>