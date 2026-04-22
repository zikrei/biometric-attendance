<aside class="sidebar d-flex flex-column" id="mobileSidebar">

    {{-- Sidebar Header (Brand + Toggle Button) --}}
    <div class="sidebar-header d-flex flex-column align-items-center position-relative py-3">
        
        <button class="btn btn-link text-white-50 p-0 d-none d-lg-flex position-absolute sidebar-toggle-btn" 
                style="top: 20px; right: 12px;" id="sidebarToggleBtn">
            {{-- CHANGE HERE: Swapped to fs-2 to make the arrow much bigger! --}}
            <i class="bi bi-chevron-double-left fs-2 toggle-icon"></i>
        </button>

        <i class="bi bi-fingerprint text-white brand-icon mb-2" style="font-size: 2rem; line-height: 1;"></i>
        
        <div class="brand-text text-center text-nowrap">
            <h5 class="text-white fw-bold mb-0">Biometric</h5>
            <small class="text-white-50" style="font-size: 0.75rem;">Attendance System</small>
        </div>
        
    </div>

    {{-- Navigation Links --}}
    <div class="sidebar-title">MAIN NAVIGATION</div>
    
    <ul class="nav flex-column gap-1 px-2 mb-auto">
        
        @php $role = Auth::user()->role?->name; @endphp

        {{-- 1. DASHBOARD (Dynamic based on role) --}}
        <li class="nav-item">
            @if($role === 'Admin')
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            @elseif($role === 'HOD')
                <a href="{{ route('hod.dashboard') }}" class="nav-link {{ request()->routeIs('hod.dashboard') ? 'active' : '' }}">
            @elseif($role === 'Integrity')
                <a href="{{ route('integrity.dashboard') }}" class="nav-link {{ request()->routeIs('integrity.dashboard') ? 'active' : '' }}">
            @else
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            @endif
                <i class="bi bi-house-door"></i>
                <span>Dashboard</span>
            </a>
        </li>

        {{-- 2. USER MANAGEMENT (Admin Only) --}}
        @if($role === 'Admin')
        <li class="nav-item">
            <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <i class="bi bi-people"></i>
                <span>User Management</span>
            </a>
        </li>
        @endif

        {{-- 3. ATTENDANCE RECORDS (Staff & HOD) --}}
        @if(in_array($role, ['Staff', 'HOD']))
        <li class="nav-item">
            <a href="{{ route('attendance.list') }}" class="nav-link {{ request()->routeIs('attendance.*') ? 'active' : '' }}">
                <i class="bi bi-calendar-check"></i>
                <span>Attendance Records</span>
            </a>
        </li>
        @endif

        {{-- 4. ATTENDANCE APPROVALS (HOD & Integrity) --}}
        @if($role === 'HOD')
        <li class="nav-item">
            <a href="{{ route('hod.approvals') }}" class="nav-link {{ request()->routeIs('hod.*') && !request()->routeIs('hod.dashboard') ? 'active' : '' }}">
                <i class="bi bi-check-square"></i>
                <span>Attendance Approvals</span>
            </a>
        </li>
        @elseif($role === 'Integrity')
        <li class="nav-item">
            <a href="{{ route('integrity.approvals') }}" class="nav-link {{ request()->routeIs('integrity.*') && !request()->routeIs('integrity.dashboard') ? 'active' : '' }}">
                <i class="bi bi-shield-check"></i>
                <span>Attendance Approvals</span>
            </a>
        </li>
        @endif

        {{-- 5. REPORTS (Admin, Integrity, AND NOW HOD!) --}}
        @if(in_array($role, ['Admin', 'Integrity', 'HOD']))
        <li class="nav-item">
            <a href="{{ route('reports.index') }}" class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                <i class="bi bi-file-earmark-text"></i>
                <span>Reports</span>
            </a>
        </li>
        @endif

    </ul>
</aside>