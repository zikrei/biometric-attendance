<aside class="sidebar">
    @php
        $role = auth()->user()->role?->name;
    @endphp

    <div class="menu-section">
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