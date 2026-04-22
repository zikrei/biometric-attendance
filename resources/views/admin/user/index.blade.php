@extends('layouts.app')

@section('title', 'User Management')
@section('page_title', 'User Management')
@section('page_subtitle', 'View, create, update, and remove user accounts.')

@section('content')

    {{-- The mt-4 (Margin Top) here forces the gap below the topbar --}}
    <div class="mt-4">
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0 fw-bold text-secondary">User List</h5>
        
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admin.users.print') }}" target="_blank" class="btn btn-outline-primary shadow-sm">
                    <i class="bi bi-printer me-1"></i> Print List
                </a>
                <a href="{{ route('admin.users.create') }}" class="btn btn-primary shadow-sm">
                    <i class="bi bi-plus-lg me-1"></i> Create User
                </a>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Full Name</th>
                                <th>Email</th>
                                <th>Department</th>
                                <th>Device ID</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td class="fw-semibold">{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->department?->name ?? 'Not Assigned' }}</td>
                                    <td>
                                        @if($user->device_user_id)
                                            <span class="fw-bold">{{ $user->device_user_id }}</span>
                                        @else
                                            <span class="text-muted small">Not Assigned</span>
                                        @endif
                                    </td>
                                    
                                    <td>
                                        @php $roleName = $user->role?->name; @endphp
                                        
                                        @if($roleName === 'Admin')
                                            <span class="badge bg-primary">👨‍💻 Admin</span>
                                        @elseif($roleName === 'HOD')
                                            <span class="badge bg-warning text-dark">👨‍💼 HOD</span>
                                        @elseif($roleName === 'Staff')
                                            <span class="badge bg-success">👨‍🔧 Staff</span>
                                        @elseif($roleName === 'Integrity')
                                            <span class="badge" style="background-color: #6f42c1;">🕵️ Integrity Unit</span>
                                        @else
                                            <span class="badge bg-secondary">Not Assigned</span>
                                        @endif
                                    </td>
                                    
                                    <td>
                                        @if($user->status == 'Active')
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                    
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-pencil-square"></i> Edit
                                            </a>
                                            
                                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete the account for {{ $user->name }}? This action cannot be undone.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="bi bi-trash"></i> Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
    </div>
@endsection