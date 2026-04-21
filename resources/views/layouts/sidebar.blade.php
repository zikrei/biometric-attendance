<aside class="sidebar">

    {{-- CSS for handling the collapse animation cleanly --}}
    <style>
        .brand-text { transition: opacity 0.2s; }
        
        /* What happens when the sidebar is collapsed */
        body.sidebar-collapsed .brand-text { display: none !important; }
        
        body.sidebar-collapsed .sidebar-header {
            flex-direction: column !important;
            justify-content: center !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
            gap: 10px;
        }
    </style>

    {{-- Sidebar Header (Brand + Toggle Button) --}}
    <div class="sidebar-header d-flex align-items-center justify-content-between px-3 py-3" style="border-bottom: 1px solid rgba(255,255,255,0.05);">
        
        {{-- The Icon & Text --}}
        <div class="d-flex align-items-center gap-2 overflow-hidden">
            <i class="bi bi-fingerprint text-white flex-shrink-0" style="font-size: 2rem;"></i>
            <div class="brand-text text-nowrap">
                <h5 class="text-white fw-bold mb-0" style="letter-spacing: 0.5px;">Biometric</h5>
                <small class="text-white-50" style="font-size: 0.75rem;">Attendance System</small>
            </div>
        </div>
        
        {{-- The NEW Desktop Toggle Button (Inside Sidebar) --}}
        <button class="btn btn-link text-white-50 p-0 d-none d-lg-block flex-shrink-0" id="sidebarToggleBtn">
            <i class="bi bi-list fs-4"></i>
        </button>
    </div>

    {{-- Your existing sidebar navigation links start below here... --}}
    <ul class="nav flex-column..."></ul>
    @php
        $role = auth()->user()->role?->name;
    @endphp

    <div class="menu-section">

    {{-- NEW: Brand Block inside the Sidebar --}}
        <div class="sidebar-title">Main Navigation</div>

        <nav class="nav flex-column">
            @if($role === 'Admin')
                <a href="{{ route('admin.dashboard') }}"
                   class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-house-door"></i>
                    <span>Dashboard</span>
                </a>

                <a href="{{ url('/admin/users') }}"
                   class="nav-link {{ request()->is('admin/users*') ? 'active' : '' }}">
                    <i class="bi bi-people"></i>
                    <span>User Management</span>
                </a>

                <a href="{{ url('/admin/reports') }}"
                   class="nav-link {{ request()->is('admin/reports*') ? 'active' : '' }}">
                    <i class="bi bi-file-earmark-text"></i>
                    <span>Reports</span>
                </a>

            @elseif($role === 'HOD')
                <a href="{{ route('hod.dashboard') }}"
                   class="nav-link {{ request()->routeIs('hod.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-house-door"></i>
                    <span>Dashboard</span>
                </a>

                <a href="{{ url('/attendance') }}"
                   class="nav-link {{ request()->is('attendance*') ? 'active' : '' }}">
                    <i class="bi bi-calendar-check"></i>
                    <span>Attendance Records</span>
                </a>

                <a href="{{ route('hod.approvals') }}"
                   class="nav-link {{ request()->routeIs('hod.approvals') ? 'active' : '' }}">
                    <i class="bi bi-check2-square"></i>
                    <span>Attendance Approvals</span>
                </a>

            @elseif($role === 'Staff')
                <a href="{{ route('dashboard') }}"
                   class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-house-door"></i>
                    <span>Dashboard</span>
                </a>

                <a href="{{ url('/attendance') }}"
                   class="nav-link {{ request()->is('attendance*') ? 'active' : '' }}">
                    <i class="bi bi-calendar-check"></i>
                    <span>Attendance Records</span>
                </a>

            @elseif($role === 'Integrity')
                <a href="{{ route('integrity.dashboard') }}"
                   class="nav-link {{ request()->routeIs('integrity.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-house-door"></i>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('integrity.approvals') }}"
                   class="nav-link {{ request()->routeIs('integrity.approvals') ? 'active' : '' }}">
                    <i class="bi bi-shield-check"></i>
                    <span>Attendance Approvals</span>
                </a>

                <a href="{{ url('/admin/reports') }}"
                   class="nav-link {{ request()->is('admin/reports*') ? 'active' : '' }}">
                    <i class="bi bi-file-earmark-text"></i>
                    <span>Reports</span>
                </a>
            @endif
        </nav>
    </div>
</aside>