<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Attendance Management System')</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background-color: #f4f6f9;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .app-wrapper {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .topbar {
            height: 64px;
            background: #ffffff;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1.25rem;
            position: sticky;
            top: 0;
            z-index: 1030;
        }

        .brand-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #1f2937;
            margin: 0;
        }

        .layout-body {
            display: flex;
            flex: 1;
        }

        .sidebar {
            width: 260px;
            min-height: calc(100vh - 64px);
            background: #111827;
            color: #ffffff;
            padding: 1.25rem 1rem;
        }

        .sidebar .sidebar-title {
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #9ca3af;
            margin-bottom: 1rem;
        }

        .sidebar .nav-link {
            color: #d1d5db;
            border-radius: 10px;
            padding: 0.75rem 0.9rem;
            margin-bottom: 0.4rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.2s ease;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: #1f2937;
            color: #ffffff;
        }

        .sidebar .menu-section {
            margin-bottom: 1.5rem;
        }

        .main-content {
            flex: 1;
            padding: 1.5rem;
        }

        .page-header {
            margin-bottom: 1.5rem;
        }

        .page-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #111827;
            margin-bottom: 0.25rem;
        }

        .page-subtitle {
            color: #6b7280;
            margin: 0;
        }

        .content-card {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 4px 14px rgba(15, 23, 42, 0.04);
        }

        .footer {
            background: #ffffff;
            border-top: 1px solid #e5e7eb;
            padding: 0.9rem 1.5rem;
            font-size: 0.9rem;
            color: #6b7280;
        }

        .user-badge {
            background: #eef2ff;
            color: #3730a3;
            font-weight: 600;
            border-radius: 999px;
            padding: 0.45rem 0.85rem;
            font-size: 0.85rem;
        }

        .mobile-sidebar-toggle {
            display: none;
        }

        @media (max-width: 991.98px) {
            .sidebar {
                display: none;
            }

            .mobile-sidebar-toggle {
                display: inline-flex;
                align-items: center;
                justify-content: center;
            }

            .main-content {
                padding: 1rem;
            }
        }

        .mobile-sidebar {
            background: #111827;
        }

        .mobile-sidebar .nav-link {
            color: #d1d5db;
            border-radius: 10px;
            margin-bottom: 0.35rem;
        }

        .mobile-sidebar .nav-link:hover {
            background: #1f2937;
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="app-wrapper">
        <!-- Top Navbar -->
        <header class="topbar">
            <div class="d-flex align-items-center gap-2">
                <button class="btn btn-outline-secondary mobile-sidebar-toggle" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar" aria-controls="mobileSidebar">
                    <i class="bi bi-list"></i>
                </button>
                <h1 class="brand-title mb-0">Biometric Attendance Management System</h1>
            </div>

            <div class="d-flex align-items-center gap-3">
                <span class="user-badge">
                    <i class="bi bi-person-circle me-1"></i> Guest User
                </span>
                <a href="#" class="btn btn-sm btn-outline-danger">
                    <i class="bi bi-box-arrow-right me-1"></i> Logout
                </a>
            </div>
        </header>

        <div class="layout-body">
            <!-- Desktop Sidebar -->
            <aside class="sidebar">
                <div class="menu-section">
                    <div class="sidebar-title">Main Navigation</div>
                    <nav class="nav flex-column">
                        @if(auth()->user()->role === 'admin')
                            <a href="{{ url('/admin/dashboard') }}" class="nav-link">
                                <i class="bi bi-house-door"></i>
                                <span>Admin Dashboard</span>
                            </a>
                            <a href="{{ url('/admin/users') }}" class="nav-link">
                                <i class="bi bi-person-lines-fill"></i>
                                <span>User Management</span>
                            </a>
                            <a href="{{ url('/admin/reports') }}" class="nav-link">
                                <i class="bi bi-file-earmark-text"></i>
                                <span>Reports</span>
                            </a>
                        @elseif(auth()->user()->role === 'hod')
                            <a href="{{ url('/hod/dashboard') }}" class="nav-link">
                                <i class="bi bi-house-door"></i>
                                <span>HOD Dashboard</span>
                            </a>
                        @elseif(auth()->user()->role === 'user')
                            <a href="{{ url('/user/dashboard') }}" class="nav-link">
                                <i class="bi bi-house-door"></i>
                                <span>User Dashboard</span>
                            </a>
                        @elseif(auth()->user()->role === 'integrity')
                            <a href="{{ url('/integrity/dashboard') }}" class="nav-link">
                                <i class="bi bi-house-door"></i>
                                <span>Integrity Dashboard</span>
                            </a>
                        @endif
                    </nav>
                </div>
            </aside>
            <!-- Main Content -->
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

        <!-- Footer -->
        <footer class="footer text-center">
            &copy; {{ date('Y') }} Biometric Attendance Management System. All rights reserved.
        </footer>
    </div>

    <!-- Mobile Sidebar -->
    <div class="offcanvas offcanvas-start mobile-sidebar text-white" tabindex="-1" id="mobileSidebar" aria-labelledby="mobileSidebarLabel">
        <div class="offcanvas-header border-bottom border-secondary">
            <h5 class="offcanvas-title" id="mobileSidebarLabel">Menu</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>

        <div class="offcanvas-body">
            <nav class="nav flex-column">
                <a href="{{ url('/dashboard') }}" class="nav-link">
                    <i class="bi bi-speedometer2 me-2"></i> Dashboard
                </a>
                <a href="#" class="nav-link">
                    <i class="bi bi-calendar-check me-2"></i> Attendance
                </a>
                <a href="#" class="nav-link">
                    <i class="bi bi-exclamation-circle me-2"></i> Discrepancies
                </a>
                <a href="#" class="nav-link">
                    <i class="bi bi-file-earmark-text me-2"></i> Monthly Reports
                </a>
                <a href="#" class="nav-link">
                    <i class="bi bi-people me-2"></i> Manage Users
                </a>
                <a href="#" class="nav-link">
                    <i class="bi bi-check2-square me-2"></i> Approval Queue
                </a>
            </nav>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>