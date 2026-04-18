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
                <a href="{{ route('hod.dashboard') }}" class="nav-link">
                    <i class="bi bi-house-door"></i>
                    <span>HOD Dashboard</span>
                </a>

            @elseif($role === 'Staff')
                <a href="{{ route('dashboard') }}" class="nav-link">
                    <i class="bi bi-house-door"></i>
                    <span>Staff Dashboard</span>
                </a>

            @elseif($role === 'Integrity')
                <a href="{{ route('integrity.dashboard') }}" class="nav-link">
                    <i class="bi bi-house-door"></i>
                    <span>Integrity Dashboard</span>
                </a>
                {{-- Added Reports link for Integrity Unit --}}
                <a href="{{ url('/admin/reports') }}" class="nav-link">
                    <i class="bi bi-file-earmark-text"></i>
                    <span>Reports</span>
                </a>
            @endif

            {{-- Hide the Attendance Log from the Integrity Unit --}}
            @if($role !== 'Integrity')
                <div class="sidebar-title mt-4">General</div>
                <a href="{{ url('/attendance') }}" class="nav-link">
                    <i class="bi bi-calendar-check"></i>
                    <span>Attendance Log</span>
                </a>
            @endif
        </nav>
    </div>
</aside>