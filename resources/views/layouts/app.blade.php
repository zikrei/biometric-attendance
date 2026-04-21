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
        
        {{-- 1. THE FULL-HEIGHT SIDEBAR --}}
        @if(Auth::check())
            @include('layouts.sidebar')
        @endif

        {{-- 2. THE RIGHT CONTENT WRAPPER --}}
        <div class="content-wrapper">
            
            <header class="topbar">
                <div class="topbar-left d-flex align-items-center gap-3">
                    <button class="btn btn-outline-secondary mobile-sidebar-toggle d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar">
                        <i class="bi bi-list"></i>
                    </button>

                    @auth
                        <div class="d-flex align-items-center gap-2 px-3 py-1 rounded" style="border: 1px solid #14b8a6; background-color: #f0fdfa; color: #0f766e;">
                            <i class="bi bi-person-vcard fs-5"></i>
                            <span class="fw-bold">{{ Auth::user()->role?->name ?? 'Staff' }}</span>
                        </div>
                    @endauth
                </div>

                <div class="topbar-right d-flex align-items-center">
                    @auth
                        <div class="dropdown">
                            <button class="btn border-0 dropdown-toggle d-flex align-items-center gap-2 bg-transparent p-1" 
                                    type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                
                                <div class="text-end d-none d-sm-block me-1">
                                    <small class="text-muted d-block" style="font-size: 0.75rem; margin-bottom: -3px;">Welcome,</small>
                                    <span class="fw-bold text-dark" style="font-size: 0.9rem;">{{ Auth::user()->name }}</span>
                                </div>
                                
                                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=6d28d9&color=fff&bold=true" 
                                     alt="Profile" class="rounded-circle shadow-sm" width="38" height="38">
                            </button>

                            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3 mt-2" aria-labelledby="userDropdown">
                                <li>
                                    <a class="dropdown-item py-2 d-flex align-items-center" href="{{ route('profile.edit') }}">
                                        <i class="bi bi-person-gear me-2 text-primary"></i> Profile Settings
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
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

            {{-- MAIN PAGE CONTENT --}}
            <main class="main-content">
                
                {{-- If you have page actions (like the Print button), we keep them but align them to the right --}}
                @auth
                    @hasSection('page_actions')
                        <div class="d-flex justify-content-end mb-3">
                            @yield('page_actions')
                        </div>
                    @endif
                @endauth

                {{-- The main page content loads here --}}
                @yield('content')
                
            </main>

            <footer class="footer">
                <span>&copy; {{ date('Y') }} Biometric Attendance Management System. All rights reserved.</span>
            </footer>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sidebarToggleBtn = document.getElementById('sidebarToggleBtn');

            if (sidebarToggleBtn) {
                sidebarToggleBtn.addEventListener('click', function () {
                    document.body.classList.toggle('sidebar-collapsed');
                });
            }
        });
    </script>
</body>
</html>