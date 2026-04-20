<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#ffffff">
    <title>@yield('title', 'Biometric Attendance System')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased">
    <div class="app-wrapper">
        <header class="topbar">
            <div class="topbar-left">
                <button
                    class="btn btn-outline-secondary mobile-sidebar-toggle d-lg-none"
                    type="button"
                    data-bs-toggle="offcanvas"
                    data-bs-target="#mobileSidebar"
                    aria-controls="mobileSidebar"
                    aria-label="Toggle mobile menu"
                >
                    <i class="bi bi-list"></i>
                </button>

                <button
                    class="btn btn-light desktop-sidebar-toggle d-none d-lg-flex align-items-center justify-content-center"
                    type="button"
                    id="desktopToggleBtn"
                    aria-label="Toggle desktop sidebar"
                >
                    <i class="bi bi-list"></i>
                </button>

                <div class="brand-block">
                    <h1 class="brand-title mb-0">Biometric Attendance</h1>
                    <small class="brand-subtitle">Management Platform</small>
                </div>
            </div>

            <div class="topbar-right">
                {{-- Only show these elements if the user is LOGGED IN --}}
                @auth
                    <div class="user-badge">
                        <i class="bi bi-person-circle"></i>
                        <span>{{ Auth::user()->name }}</span>
                    </div>

                    <a href="{{ route('profile.edit') }}" class="btn btn-sm btn-outline-primary action-btn">
                        <i class="bi bi-person-gear me-1"></i> Account Settings
                    </a>

                    <form method="POST" action="{{ route('logout') }}" class="d-inline mb-0">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-danger action-btn">
                            <i class="bi bi-box-arrow-right me-1"></i> Secure Logout
                        </button>
                    </form>
                @endauth
            </div>
        </header>

        <div class="layout-body">
            @if(Auth::check())
                @include('layouts.sidebar')
            @endif

            <main class="main-content">
                {{-- Only show the page headers to LOGGED IN users --}}
                @auth
                    <div class="page-header">
                        <div class="header-titles">
                            <h2 class="page-title">@yield('page_title', 'System Overview')</h2>
                            <p class="page-subtitle">@yield('page_subtitle', 'Monitor and manage biometric records.')</p>
                        </div>
                        <div class="header-actions">
                            @yield('page_actions')
                        </div>
                    </div>
                @endauth

                @yield('content')
  
            </main>
        </div>

        <footer class="footer text-center">
            <span>&copy; {{ date('Y') }} Biometric Attendance System. All rights reserved.</span>
        </footer>
    </div>

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