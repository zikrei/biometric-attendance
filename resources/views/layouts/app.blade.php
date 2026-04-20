<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Attendance Management System')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body { background-color: #f4f6f9; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; overflow-x: hidden; }
        .app-wrapper { min-height: 100vh; display: flex; flex-direction: column; }
        .topbar { height: 64px; background: #ffffff; border-bottom: 1px solid #e5e7eb; display: flex; align-items: center; justify-content: space-between; padding: 0 1.25rem; position: sticky; top: 0; z-index: 1030; }
        .brand-title { font-size: 1.1rem; font-weight: 700; color: #1f2937; margin: 0; }
        .layout-body { display: flex; flex: 1; }
        
        /* SIDEBAR STYLES WITH TRANSITIONS */
        .sidebar { width: 260px; min-height: calc(100vh - 64px); background: #111827; color: #ffffff; padding: 1.25rem 1rem; transition: width 0.3s ease, padding 0.3s ease; overflow: hidden; white-space: nowrap; }
        .sidebar .sidebar-title { font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.08em; color: #9ca3af; margin-bottom: 1rem; transition: opacity 0.2s ease; }
        .sidebar .nav-link { color: #d1d5db; border-radius: 10px; padding: 0.75rem 0.9rem; margin-bottom: 0.4rem; display: flex; align-items: center; gap: 0.75rem; transition: all 0.2s ease; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { background: #1f2937; color: #ffffff; }
        .sidebar .menu-section { margin-bottom: 1.5rem; }
        
        /* --- NEW: COLLAPSED SIDEBAR STYLES --- */
        body.sidebar-collapsed .sidebar { width: 80px !important; padding: 1.25rem 0.5rem; }
        
        /* Force titles and dividers to completely vanish */
        body.sidebar-collapsed .sidebar .sidebar-title,
        body.sidebar-collapsed .sidebar hr { opacity: 0; pointer-events: none; height: 0; margin: 0; padding: 0; border: none; overflow: hidden; display: none; }
        
        /* Hide spans normally */
        body.sidebar-collapsed .sidebar .nav-link span { display: none; }
        
        /* The Magic Fix: font-size: 0 hides any orphaned text! */
        body.sidebar-collapsed .sidebar .nav-link { justify-content: center; padding: 0.85rem 0; gap: 0; font-size: 0; }
        
        /* Restore the icon size and strip away any awkward margins (like me-2) */
        body.sidebar-collapsed .sidebar .nav-link i { font-size: 1.3rem; margin: 0 !important; }
        /* ------------------------------------- */

        .main-content { flex: 1; padding: 1.5rem; transition: all 0.3s ease; width: 100%; overflow-x: auto; }
        .page-header { margin-bottom: 1.5rem; }
        .page-title { font-size: 1.5rem; font-weight: 700; color: #111827; margin-bottom: 0.25rem; }
        .page-subtitle { color: #6b7280; margin: 0; }
        .content-card { background: #ffffff; border: 1px solid #e5e7eb; border-radius: 16px; padding: 1.5rem; box-shadow: 0 4px 14px rgba(15, 23, 42, 0.04); }
        .footer { background: #ffffff; border-top: 1px solid #e5e7eb; padding: 0.9rem 1.5rem; font-size: 0.9rem; color: #6b7280; }
        .user-badge { background: #eef2ff; color: #3730a3; font-weight: 600; border-radius: 999px; padding: 0.45rem 0.85rem; font-size: 0.85rem; }
        
        .mobile-sidebar-toggle { display: none; }
        @media (max-width: 991.98px) {
            .sidebar { display: none !important; }
            .desktop-sidebar-toggle { display: none !important; } /* Hide desktop button on phones */
            .mobile-sidebar-toggle { display: inline-flex; align-items: center; justify-content: center; }
            .main-content { padding: 1rem; }
        }
        
        .mobile-sidebar { background: #111827; }
        .mobile-sidebar .nav-link { color: #d1d5db; border-radius: 10px; margin-bottom: 0.35rem; }
        .mobile-sidebar .nav-link:hover { background: #1f2937; color: #fff; }
    </style>
</head>
<body>
    <div class="app-wrapper">
        <header class="topbar">
            <div class="d-flex align-items-center gap-2">
                {{-- Original Mobile Toggle --}}
                <button class="btn btn-outline-secondary mobile-sidebar-toggle" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar" aria-controls="mobileSidebar">
                    <i class="bi bi-list"></i>
                </button>

                {{-- NEW: Desktop Toggle Button --}}
                <button class="btn btn-light border-0 desktop-sidebar-toggle d-none d-lg-flex align-items-center justify-content-center" type="button" id="desktopToggleBtn" style="width: 40px; height: 40px;">
                    <i class="bi bi-list fs-5"></i>
                </button>
                
                <h1 class="brand-title mb-0 ms-1">Biometric Attendance Management System</h1>
            </div>

            <div class="d-flex align-items-center gap-3">
                <span class="user-badge">
                    <i class="bi bi-person-circle me-1"></i> {{ Auth::user()?->name ?? 'Guest User' }}
                </span>
                
                @auth
                    <a href="{{ route('profile.edit') }}" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-person-gear me-1"></i> Profile
                    </a>
                @endauth
                
                <form method="POST" action="{{ route('logout') }}" class="d-inline mb-0">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-danger">
                        <i class="bi bi-box-arrow-right me-1"></i> Logout
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
                    <h2 class="page-title">@yield('page_title', 'Dashboard')</h2>
                    <p class="page-subtitle">@yield('page_subtitle', 'Welcome to the attendance management system.')</p>
                </div>

                <div class="content-card">
                    @yield('content')
                </div>
            </main>
        </div>

        <footer class="footer text-center">
            &copy; {{ date('Y') }} Biometric Attendance Management System. All rights reserved.
        </footer>
    </div>

    @if(Auth::check())
    <div class="offcanvas offcanvas-start mobile-sidebar text-white" tabindex="-1" id="mobileSidebar" aria-labelledby="mobileSidebarLabel">
        <div class="offcanvas-header border-bottom border-secondary">
            <h5 class="offcanvas-title" id="mobileSidebarLabel">Menu</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>

        <div class="offcanvas-body">
            <nav class="nav flex-column">
                @php $role = auth()->user()->role?->name; @endphp
                
                @if($role === 'Admin')
                    <a href="{{ route('admin.dashboard') }}" class="nav-link"><i class="bi bi-house-door me-2"></i> Admin Dashboard</a>
                    <a href="{{ url('/admin/users') }}" class="nav-link"><i class="bi bi-people me-2"></i> User Management</a>
                    <a href="{{ url('/admin/reports') }}" class="nav-link"><i class="bi bi-file-earmark-text me-2"></i> Reports</a>
                @elseif($role === 'HOD')
                    <a href="{{ route('hod.dashboard') }}" class="nav-link"><i class="bi bi-house-door me-2"></i> HOD Dashboard</a>
                @elseif($role === 'Staff')
                    <a href="{{ route('dashboard') }}" class="nav-link"><i class="bi bi-house-door me-2"></i> Staff Dashboard</a>
                @elseif($role === 'Integrity')
                @elseif($role === 'Integrity')
                    <a href="{{ route('integrity.dashboard') }}" class="nav-link"><i class="bi bi-house-door me-2"></i> Integrity Dashboard</a>
                    <a href="{{ route('integrity.approvals') }}" class="nav-link"><i class="bi bi-shield-check me-2"></i> HOD Approvals</a>
                    <a href="{{ url('/admin/reports') }}" class="nav-link"><i class="bi bi-file-earmark-text me-2"></i> System Reports</a>
                @endif
                
                @if($role !== 'Integrity' && $role !== 'Admin')
                    <hr class="border-secondary">
                    <a href="{{ url('/attendance') }}" class="nav-link"><i class="bi bi-calendar-check me-2"></i> Attendance Log</a>
                @endif
            </nav>
        </div>
    </div>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    {{-- NEW: JavaScript to power the desktop sidebar toggle --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const desktopToggleBtn = document.getElementById('desktopToggleBtn');
            
            if (desktopToggleBtn) {
                desktopToggleBtn.addEventListener('click', function() {
                    // This toggles the 'sidebar-collapsed' class on the body tag
                    document.body.classList.toggle('sidebar-collapsed');
                });
            }
        });
    </script>
</body>
</html>