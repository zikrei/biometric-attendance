<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#ffffff">
    <title>@yield('title', 'Biometric Attendance Management System')</title>

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
                    aria-label="Open mobile navigation menu"
                >
                    <i class="bi bi-list"></i>
                </button>

                <button
                    class="btn btn-light desktop-sidebar-toggle d-none d-lg-flex align-items-center justify-content-center"
                    type="button"
                    id="desktopToggleBtn"
                    aria-label="Toggle sidebar navigation"
                >
                    <i class="bi bi-list"></i>
                </button>

                <div class="brand-block">
                    <h1 class="brand-title mb-0">Biometric Attendance Management</h1>
                    <small class="brand-subtitle">System Platform</small>
                </div>
            </div>

            <div class="topbar-right">
                {{-- Only show this dropdown if the user is LOGGED IN --}}
                @auth
                    <div class="dropdown">
                        {{-- The Clickable User Badge --}}
                        <button class="btn border-0 dropdown-toggle d-flex align-items-center gap-2 rounded-pill px-3 py-2"
                                type="button"
                                id="userDropdown"
                                data-bs-toggle="dropdown"
                                aria-expanded="false"
                                style="background-color: rgba(13, 110, 253, 0.1); color: #0d6efd;">
                            <i class="bi bi-person-circle fs-5"></i>
                            <span class="fw-semibold">{{ Auth::user()->name }}</span>
                        </button>

                        {{-- The Dropdown Menu --}}
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3 mt-2" aria-labelledby="userDropdown">
                            {{-- Account Settings Link --}}
                            <li>
                                <a class="dropdown-item py-2 d-flex align-items-center" href="{{ route('profile.edit') }}">
                                    <i class="bi bi-person-gear me-2 text-primary"></i> Profile Settings
                                </a>
                            </li>

                            {{-- Divider Line --}}
                            <li><hr class="dropdown-divider"></li>

                            {{-- Secure Logout Form --}}
                            <li>
                                <form method="POST" action="{{ route('logout') }}" class="mb-0">
                                    @csrf
                                    <button type="submit" class="dropdown-item py-2 d-flex align-items-center text-danger">
                                        <i class="bi bi-box-arrow-right me-2"></i> Sign Out
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
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
                            <h2 class="page-title">@yield('page_title', 'Dashboard Overview')</h2>
                            <p class="page-subtitle">@yield('page_subtitle', 'Monitor and manage attendance records and system activities.')</p>
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
            <span>&copy; {{ date('Y') }} Biometric Attendance Management System. All rights reserved.</span>
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