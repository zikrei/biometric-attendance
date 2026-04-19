@extends('layouts.app')

@section('title', 'Create User')

@section('page_title', 'Create New User')

@section('page_subtitle', 'Fill in the details to create a new user.')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="mb-0">Add New User</h5>
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back to List
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            
            {{-- Display Validation Errors --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label fw-bold">Name</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" placeholder="e.g. John Doe" required>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label fw-bold">Email Address</label>
                        <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" placeholder="e.g. john@example.com" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="role_id" class="form-label fw-bold">Assign Role</label>
                        <select name="role_id" id="role_id" class="form-select" required>
                            <option value="" disabled selected>-- Select a Role --</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="department_id" class="form-label fw-bold">Assign Department</label>
                        <select name="department_id" id="department_id" class="form-select" required>
                            <option value="" disabled selected>-- Select a Department --</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="mb-4">
                    <label for="password" class="form-label fw-bold">Temporary Password</label>
                    <input type="password" name="password" id="password" class="form-control" placeholder="Minimum 6 characters" required>
                </div>
                
                <hr>

                <div class="d-flex justify-content-end gap-2 mt-3">
                    <button type="reset" class="btn btn-light">Clear Form</button>
                    <button type="submit" class="btn btn-dark">Save User</button>
                </div>
            </form>
        </div>
    </div>
@endsection