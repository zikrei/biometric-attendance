@extends('layouts.app')

@section('title', 'Edit User')

@section('page_title', 'Edit User: ' . $user->name)

@section('page_subtitle', 'Update the user’s account details, role, department, and status.')

@section('content')
    {{-- 
      PHASE 1: NAVIGATION & CONTEXTUAL HEADER
      OBJECTIVE: Establish page identity and provide a return path to the administrative registry.
    --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="mb-0">Edit User</h5>
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back to User List
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            
            {{-- 
              PHASE 2: VALIDATION FEEDBACK MECHANISM
              OBJECTIVE: Render server-side validation errors for failed update attempts.
            --}}
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
                @method('PUT') {{-- REQUIRED FOR RESTFUL UPDATE LOGIC IN LARAVEL --}}
                
                {{-- 
                  PHASE 3: CORE IDENTITY MODIFICATION
                  OBJECTIVE: Pre-fill and allow updates to primary user identification.
                  PROCEDURE: Uses old() with fallback to $user attributes to maintain state after validation.
                --}}
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label fw-bold">Full Name</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label fw-bold">Email Address</label>
                        <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                    </div>
                </div>

                {{-- 
                  PHASE 4: HIERARCHICAL RE-ASSIGNMENT
                  OBJECTIVE: Update the user's organizational placement and permission tier.
                  LOGIC: Checks existing relationship IDs to set the 'selected' state in the UI.
                --}}
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="role_id" class="form-label fw-bold">Role</label>
                        <select name="role_id" id="role_id" class="form-select" required>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" {{ $user->role_id == $role->id ? 'selected' : '' }}>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="department_id" class="form-label fw-bold">Department</label>
                        <select name="department_id" id="department_id" class="form-select" required>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}" {{ $user->department_id == $department->id ? 'selected' : '' }}>
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- 
                  PHASE 5: ACCOUNT STATUS & SECURITY UPDATE
                  OBJECTIVE: Manage the account lifecycle state and optional credential resets.
                  SECURITY: Implements a "null-allowed" password update to prevent accidental credential overwriting.
                --}}
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="status" class="form-label fw-bold">Status</label>
                        <select name="status" class="form-select">
                            <option value="Active" {{ $user->status == 'Active' ? 'selected' : '' }}>Active</option>
                            <option value="Inactive" {{ $user->status == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="password" class="form-label fw-bold">New Password</label>
                        <input type="password" name="password" id="password" class="form-control" placeholder="Leave this field blank to keep the current password">
                        <small class="text-muted">Enter a new password only if you want to update the current password.</small>
                    </div>
                </div>
                
                <hr>

                {{-- 
                  PHASE 6: TRANSACTION PERSISTENCE
                  OBJECTIVE: Finalize record updates or abort the administrative session.
                --}}
                <div class="d-flex justify-content-end gap-2 mt-3">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-light">Cancel</a>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
@endsection