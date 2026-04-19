<aside class="sidebar">
    <div class="menu-section">
        <div class="sidebar-title">Main Navigation</div>
        <nav class="nav flex-column">
            @php $role = auth()->user()->role?->name; @endphp

            @if($role === 'Admin')
                <a href="{{ route('admin.dashboard') }}" class="nav-link">
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

            @elseif($role === 'HOD')
                <div class="menu-section">
                    <div class="sidebar-title">HOD Menu</div>
                    <a href="{{ route('hod.dashboard') }}" class="nav-link {{ request()->routeIs('hod.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-house-door"></i>
                        <span>HOD Dashboard</span>
                    </a>
                    <a href="{{ url('/attendance') }}" class="nav-link {{ request()->is('attendance*') ? 'active' : '' }}">
                        <i class="bi bi-calendar-check"></i>
                        <span>My Attendance Log</span>
                    </a>
                    <a href="{{ route('hod.approvals') }}" class="nav-link {{ request()->routeIs('hod.approvals') ? 'active' : '' }}">
                        <i class="bi bi-check2-square"></i>
                        <span>Discrepancy Approvals</span>
                    </a>
                </div>

            @elseif($role === 'Staff')
                <a href="{{ route('dashboard') }}" class="nav-link">
                    <i class="bi bi-house-door"></i>
                    <span>Staff Dashboard</span>
                </a>

            @elseif($role === 'Integrity')
                <div class="menu-section">
                    <div class="sidebar-title">Integrity Menu</div>
                        <a href="{{ route('integrity.dashboard') }}" class="nav-link {{ request()->routeIs('integrity.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-house-door"></i>
                        <span>Integrity Dashboard</span>
                    </a>
                    <a href="{{ route('integrity.approvals') }}" class="nav-link {{ request()->routeIs('integrity.approvals') ? 'active' : '' }}">
                        <i class="bi bi-shield-check"></i>
                        <span>HOD Approvals</span>
                    </a>
                    <a href="{{ url('/admin/reports') }}" class="nav-link {{ request()->is('admin/reports*') ? 'active' : '' }}">
                        <i class="bi bi-file-earmark-text"></i>
                        <span>System Reports</span>
                    </a>
                </div>
            @endif

            {{-- Hide the Attendance Log from Integrity Unit AND Admin --}}
            @if($role !== 'Integrity' && $role !== 'Admin')
                <div class="sidebar-title mt-4">General</div>
                <a href="{{ url('/attendance') }}" class="nav-link">
                    <i class="bi bi-calendar-check"></i>
                    <span>Attendance Log</span>
                </a>
            @endif
        </nav>
    </div>
</aside>