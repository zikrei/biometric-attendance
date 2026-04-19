@extends('layouts.app')

@section('title', 'User Management')

@section('page_title', 'Manage Users')

@section('page_subtitle', 'View, create, edit, or delete users.')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="mb-0">Users List</h5>
        <a href="{{ route('admin.users.create') }}" class="btn btn-dark">+ Add User</a>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Department</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td class="fw-semibold">{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->department?->name ?? 'N/A' }}</td>
                                
                                {{-- Dynamic Role Badges --}}
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
                                        <span class="badge bg-secondary">Unassigned</span>
                                    @endif
                                </td>
                                
                                {{-- Status Badges --}}
                                <td>
                                    @if($user->status == 'Active')
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </td>
                                
                                {{-- Action Buttons --}}
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-pencil-square"></i> Edit
                                        </a>
                                        
                                        {{-- Delete Form with JavaScript Confirmation --}}
                                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete {{ $user->name }}? This action cannot be undone.');">
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
@endsection