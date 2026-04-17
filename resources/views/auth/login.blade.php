<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Biometric Attendance Management System</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #0f172a, #1d4ed8);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .login-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }

        .login-container {
            width: 100%;
            max-width: 1100px;
            background: #ffffff;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.18);
        }

        .login-left {
            background: linear-gradient(180deg, #0f172a, #1e3a8a);
            color: #ffffff;
            padding: 3rem;
            height: 100%;
        }

        .login-left h1 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .login-left p {
            color: rgba(255, 255, 255, 0.85);
            font-size: 1rem;
            line-height: 1.7;
        }

        .feature-list {
            margin-top: 2rem;
        }

        .feature-item {
            display: flex;
            align-items: flex-start;
            gap: 0.8rem;
            margin-bottom: 1.2rem;
        }

        .feature-icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 1.1rem;
        }

        .login-right {
            padding: 3rem;
            background: #f8fafc;
        }

        .login-card-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 0.5rem;
        }

        .login-card-subtitle {
            color: #64748b;
            margin-bottom: 2rem;
        }

        .form-control {
            height: 50px;
            border-radius: 12px;
            border: 1px solid #cbd5e1;
        }

        .form-control:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.15);
        }

        .input-group-text {
            border-radius: 12px 0 0 12px;
            background: #ffffff;
            border: 1px solid #cbd5e1;
        }

        .btn-login {
            height: 50px;
            border-radius: 12px;
            font-weight: 600;
            background: #2563eb;
            border: none;
        }

        .btn-login:hover {
            background: #1d4ed8;
        }

        .system-badge {
            display: inline-block;
            padding: 0.4rem 0.85rem;
            background: rgba(255, 255, 255, 0.12);
            border-radius: 999px;
            font-size: 0.85rem;
            margin-bottom: 1rem;
        }

        .login-footer-text {
            color: #64748b;
            font-size: 0.9rem;
            margin-top: 1.5rem;
        }

        @media (max-width: 991.98px) {
            .login-left,
            .login-right {
                padding: 2rem;
            }
        }

        @media (max-width: 767.98px) {
            .login-left {
                display: none;
            }

            .login-right {
                padding: 2rem 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <div class="login-container row g-0">
            <!-- Left Side -->
            <div class="col-lg-6">
                <div class="login-left">
                    <span class="system-badge">
                        <i class="bi bi-shield-lock me-1"></i> Secure Access Portal
                    </span>

                    <h1>Biometric Attendance Management System</h1>
                    <p>
                        Welcome to the web-based attendance platform. This system is designed to help
                        staff, Heads of Department, administrators, and integrity users manage attendance
                        records in a structured and efficient way.
                    </p>

                    <div class="feature-list">
                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="bi bi-fingerprint"></i>
                            </div>
                            <div>
                                <h6 class="mb-1">Biometric Attendance Support</h6>
                                <small class="text-white-50">Prepared for thumbprint attendance synchronization.</small>
                            </div>
                        </div>

                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="bi bi-person-check"></i>
                            </div>
                            <div>
                                <h6 class="mb-1">Role-Based Access</h6>
                                <small class="text-white-50">Menus and permissions will later differ by user role.</small>
                            </div>
                        </div>

                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="bi bi-file-earmark-text"></i>
                            </div>
                            <div>
                                <h6 class="mb-1">Attendance & Reports</h6>
                                <small class="text-white-50">Users will be able to review attendance, discrepancies, and monthly reports.</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side -->
            <div class="col-lg-6">
                <div class="login-right d-flex align-items-center h-100">
                    <div class="w-100">
                        <h2 class="login-card-title">Login</h2>
                        <p class="login-card-subtitle">
                            Please sign in with your registered account to continue.
                        </p>

                        {{-- Flash Message Placeholder --}}
                        @if(session('success'))
                            <div class="alert alert-success rounded-3">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger rounded-3">
                                {{ session('error') }}
                            </div>
                        @endif

                        {{-- Validation Errors Placeholder --}}
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
                                <label for="email" class="form-label fw-semibold">Email Address</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-envelope"></i>
                                    </span>
                                    <input
                                        type="email"
                                        name="email"
                                        id="email"
                                        class="form-control"
                                        placeholder="Enter your email"
                                        value="{{ old('email') }}"
                                        required
                                    >
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label fw-semibold">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-lock"></i>
                                    </span>
                                    <input
                                        type="password"
                                        name="password"
                                        id="password"
                                        class="form-control"
                                        placeholder="Enter your password"
                                        required
                                    >
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                    <label class="form-check-label" for="remember">
                                        Remember me
                                    </label>
                                </div>

                                <a href="#" class="text-decoration-none">Forgot password?</a>
                            </div>

                            <button type="submit" class="btn btn-primary btn-login w-100">
                                <i class="bi bi-box-arrow-in-right me-1"></i> Sign In
                            </button>
                        </form>

                        <p class="login-footer-text text-center">
                            UI prototype only. Authentication logic will be connected in the next step.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>