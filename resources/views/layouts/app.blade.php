<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Biometric Attendance Management System')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <div class="app-wrapper">
        <header class="topbar">
            <div class="topbar-left">
                <button
                    class="btn btn-outline-secondary mobile-sidebar-toggle"
                    type="button"
                    data-bs-toggle="offcanvas"
                    data-bs-target="#mobileSidebar"
                    aria-controls="mobileSidebar"
                >
                    <i class="bi bi-list"></i>
                </button>

                <button
                    class="btn btn-light desktop-sidebar-toggle d-none d-lg-flex align-items-center justify-content-center"
                    type="button"
                    id="desktopToggleBtn"
                    aria-label="Toggle sidebar"
                >
                    <i class="bi bi-list"></i>
                </button>

                <div class="brand-block">
                    <h1 class="brand-title mb-0">Biometric Attendance Management System</h1>
                    <small class="brand-subtitle">Attendance and reporting platform</small>
                </div>
            </div>

            <div class="topbar-right">
                <div class="user-badge">
                    <i class="bi bi-person-circle"></i>
                    <span>{{ Auth::user()?->name ?? 'Guest User' }}</span>
                </div>

                @auth
                    <a href="{{ route('profile.edit') }}" class="btn btn-sm btn-outline-primary action-btn">
                        <i class="bi bi-person-gear me-1"></i> My Profile
                    </a>
                @endauth

                <form method="POST" action="{{ route('logout') }}" class="d-inline mb-0">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-danger action-btn">
                        <i class="bi bi-box-arrow-right me-1"></i> Sign Out
                    </button>
                </form>
            </div>
        </header>

        <div class="layout-body">
            @if(Auth::check())
                @include('layouts.sidebar')
            @endif

            <main class="main-content">
                <div class="page-header">
                    <div>
                        <h2 class="page-title">@yield('page_title', 'Dashboard')</h2>
                        <p class="page-subtitle">@yield('page_subtitle', 'Welcome to the biometric attendance management system.')</p>
                    </div>
                </div>

                <div class="content-card">
                    @yield('content')
                </div>
            </main>
        </div>

        <footer class="footer text-center">
            <span>&copy; {{ date('Y') }} Biometric Attendance Management System. All rights reserved.</span>
        </footer>
    </div>

    @if(Auth::check())
        <div class="offcanvas offcanvas-start mobile-sidebar text-white" tabindex="-1" id="mobileSidebar" aria-labelledby="mobileSidebarLabel">
            <div class="offcanvas-header border-bottom border-secondary">
                <h5 class="offcanvas-title" id="mobileSidebarLabel">Navigation Menu</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>

            <div class="offcanvas-body">
                <nav class="nav flex-column">
                    @php
                        $role = auth()->user()->role?->name;
                    @endphp

                    @if($role === 'Admin')
                        <a href="{{ route('admin.dashboard') }}" class="nav-link">
                            <i class="bi bi-house-door me-2"></i> Dashboard
                        </a>
                        <a href="{{ url('/admin/users') }}" class="nav-link">
                            <i class="bi bi-people me-2"></i> User Management
                        </a>
                        <a href="{{ url('/admin/reports') }}" class="nav-link">
                            <i class="bi bi-file-earmark-text me-2"></i> Reports
                        </a>
                    @elseif($role === 'HOD')
                        <a href="{{ route('hod.dashboard') }}" class="nav-link">
                            <i class="bi bi-house-door me-2"></i> Dashboard
                        </a>
                        <a href="{{ url('/attendance') }}" class="nav-link">
                            <i class="bi bi-calendar-check me-2"></i> Attendance Records
                        </a>
                    @elseif($role === 'Staff')
                        <a href="{{ route('dashboard') }}" class="nav-link">
                            <i class="bi bi-house-door me-2"></i> Dashboard
                        </a>
                        <a href="{{ url('/attendance') }}" class="nav-link">
                            <i class="bi bi-calendar-check me-2"></i> Attendance Records
                        </a>
                    @elseif($role === 'Integrity')
                        <a href="{{ route('integrity.dashboard') }}" class="nav-link">
                            <i class="bi bi-house-door me-2"></i> Dashboard
                        </a>
                        <a href="{{ route('integrity.approvals') }}" class="nav-link">
                            <i class="bi bi-shield-check me-2"></i> Approval Records
                        </a>
                        <a href="{{ url('/admin/reports') }}" class="nav-link">
                            <i class="bi bi-file-earmark-text me-2"></i> Reports
                        </a>
                    @endif
                </nav>
            </div>
        </div>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const desktopToggleBtn = document.getElementById('desktopToggleBtn');

            if (desktopToggleBtn) {
                desktopToggleBtn.addEventListener('click', function () {
                    document.body.classList.toggle('sidebar-collapsed');
                });
            }
        });
    </script>
</body>
</html>