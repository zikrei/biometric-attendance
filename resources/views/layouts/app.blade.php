<!DOCTYPE html>
<html lang="en">
<head>
    {{-- 
      PHASE 1: DOCUMENT METADATA & DEPENDENCIES
      OBJECTIVE: Establish the technical foundation and visual layout for the application.
      PROCEDURE: Loads Bootstrap icons and Vite-compiled CSS/JS assets.
    --}}
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#ffffff">
    <title>@yield('title', 'Biometric Attendance Management System')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased">
    
    <div class="app-wrapper">
        
        {{-- 
          PHASE 2: NAVIGATION & SIDEBAR INTEGRATION
          OBJECTIVE: Render the primary navigation menu for authenticated users.
          PROCEDURE: Includes the sidebar partial and establishes the mobile overlay barrier.
        --}}
        @if(Auth::check())
            @include('layouts.sidebar')
            <div id="sidebarOverlay" class="sidebar-overlay d-lg-none"></div>
        @endif

        <div class="content-wrapper">
            
            {{-- 
              PHASE 3: TOPBAR & USER CONTEXT
              OBJECTIVE: Provide global actions, mobile menu toggles, and user identity visualization.
              COMPONENTS: Displays the user's current role badge and a dropdown for profile/logout actions.
            --}}
            <header class="topbar">
                <div class="topbar-left">
                    <button id="mobileMenuBtn" class="btn mobile-sidebar-toggle d-lg-none" type="button">
                        <i class="bi bi-list fs-2 text-dark"></i>
                    </button>

                    @auth
                        <div class="user-badge">
                            <i class="bi bi-person-vcard fs-6"></i>
                            <span>{{ Auth::user()->role?->name ?? 'Staff' }}</span>
                        </div>
                    @endauth
                </div>

                <div class="topbar-right">
                    @auth
                        <div class="dropdown">
                            <button class="btn border-0 dropdown-toggle d-flex align-items-center gap-2 bg-transparent p-1" 
                                    type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                
                                <div class="text-end d-none d-sm-block me-1">
                                    <small class="text-muted d-block" style="font-size: 0.75rem; margin-bottom: -3px;">Welcome,</small>
                                    <span class="fw-bold text-dark" style="font-size: 0.9rem;">{{ Auth::user()->name }}</span>
                                </div>
                                
                                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=8b5cf6&color=fff&bold=true" 
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

            {{-- 
              PHASE 4: DYNAMIC CONTENT YIELDING
              OBJECTIVE: Define the structural skeleton where individual page views will inject their data.
              PROCEDURE: Checks for optional page headers/actions before yielding the main 'content' block.
            --}}
            <main class="main-content">
                @if(View::hasSection('page_title'))
                    <div class="page-header">
                        <div>
                            <h1 class="page-title">@yield('page_title')</h1>
                            @if(View::hasSection('page_subtitle'))
                                <p class="page-subtitle">@yield('page_subtitle')</p>
                            @endif
                        </div>
                        
                        @if(View::hasSection('page_actions'))
                            <div class="page-actions d-flex align-items-center gap-2">
                                @yield('page_actions')
                            </div>
                        @endif
                    </div>
                @endif

                @yield('content')
            </main>

            <button id="scrollToTopBtn" title="Go to top">
                <i class="bi bi-arrow-up"></i>
            </button>

            <footer class="footer">
                <span>&copy; {{ date('Y') }} Biometric Attendance Management System. All rights reserved.</span>
            </footer>
        </div>
    </div>

    {{-- 
      PHASE 5: UI INTERACTIVITY SCRIPTS
      OBJECTIVE: Manage the behavior of the responsive sidebar navigation.
      LOGIC: Handles desktop collapsing, mobile push toggles, and overlay click-to-close events.
    --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // 1. Desktop Collapse Menu
            const sidebarToggleBtn = document.getElementById('sidebarToggleBtn');
            if (sidebarToggleBtn) {
                sidebarToggleBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    document.body.classList.toggle('sidebar-collapsed');
                });
            }

            // 2. Mobile Push Menu
            const mobileMenuBtn = document.getElementById('mobileMenuBtn');
            const mobileSidebar = document.getElementById('mobileSidebar');
            const sidebarOverlay = document.getElementById('sidebarOverlay');

            if (mobileMenuBtn && mobileSidebar) {
                mobileMenuBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    e.stopPropagation(); 
                    document.body.classList.toggle('mobile-sidebar-open');
                    if (sidebarOverlay) sidebarOverlay.classList.toggle('show');
                });
            }

            // 3. Auto-Close Mobile Menu
            if (sidebarOverlay) {
                sidebarOverlay.addEventListener('click', function () {
                    document.body.classList.remove('mobile-sidebar-open');
                    sidebarOverlay.classList.remove('show');
                });
            }
        });
    </script>
</body>
</html>