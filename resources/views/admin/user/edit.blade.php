@extends('layouts.app')

@section('title', 'Edit User')

@section('page_title', 'Edit User: ' . $user->name)

@section('page_subtitle', 'Update user details, roles, and department.')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="mb-0">Edit User Details</h5>
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back to List
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT') {{-- REQUIRED FOR UPDATING DATA IN LARAVEL --}}
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label fw-bold">Name</label>
                        {{-- Pre-filled with existing data using value="{{ $user->name }}" --}}
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label fw-bold">Email Address</label>
                        <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="role_id" class="form-label fw-bold">Assign Role</label>
                        <select name="role_id" id="role_id" class="form-select" required>
                            @foreach($roles as $role)
                                {{-- We check if the user's role matches the loop to set it as 'selected' --}}
                                <option value="{{ $role->id }}" {{ $user->role_id == $role->id ? 'selected' : '' }}>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="department_id" class="form-label fw-bold">Assign Department</label>
                        <select name="department_id" id="department_id" class="form-select" required>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}" {{ $user->department_id == $department->id ? 'selected' : '' }}>
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="status" class="form-label fw-bold">Account Status</label>
                        <select name="status" id="status" class="form-select">
                            <option value="active" {{ $user->status == 'active' || $user->status == null ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ $user->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="password" class="form-label fw-bold">New Password (Optional)</label>
                        <input type="password" name="password" id="password" class="form-control" placeholder="Leave blank to keep current password">
                        <small class="text-muted">Only fill this if you want to change the user's password.</small>
                    </div>
                </div>
                
                <hr>

                <div class="d-flex justify-content-end gap-2 mt-3">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-light">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update User</button>
                </div>
            </form>
        </div>
    </div>
@endsection