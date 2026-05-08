@extends('layouts.app')

@section('title', 'Create User')

@section('page_title', 'Create User')

@section('page_subtitle', 'Complete the form below to create a new user account.')

@section('content')
    {{-- 
      PHASE 1: NAVIGATION & CONTEXTUAL HEADER
      OBJECTIVE: Establish page identity and provide an immediate exit route to the user registry.
    --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="mb-0">Create User</h5>
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back to User List
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            
            {{-- 
              PHASE 2: VALIDATION FEEDBACK MECHANISM
              OBJECTIVE: Provide real-time reporting of submission errors to the administrator.
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

            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf
                
                {{-- PHASE 3: CORE IDENTITY ATTRIBUTES --}}
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label fw-bold">Full Name</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" placeholder="e.g. Ahmad Bin Ali" required>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label fw-bold">Email Address</label>
                        <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" placeholder="e.g. user@company.com" required>
                    </div>
                </div>

                {{-- 
                  PHASE 4: HIERARCHICAL & ORGANIZATIONAL MAPPING
                  OBJECTIVE: Assign permissions and departmental location while supporting on-the-fly expansion.
                --}}
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="role_id" class="form-label fw-bold">Role</label>
                        <select name="role_id" id="role_id" class="form-select" required>
                            <option value="" disabled selected>Select a role</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="department_id" class="form-label fw-bold">Department</label>
                        <select name="department_id" id="department_id" class="form-select" required>
                            <option value="" disabled selected>Select a department</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                            @endforeach
                            <option value="new" class="text-primary fw-bold">+ Add New Department</option>
                        </select>
                        
                        {{-- Hidden Input for Dynamic Department Creation --}}
                        <div class="mt-2 d-none" id="new_department_wrapper">
                            <input type="text" name="new_department_name" id="new_department_name" class="form-control border-primary" placeholder="Type new department name here...">
                        </div>
                    </div>
                </div>
                
                {{-- 
                  PHASE 5: HARDWARE INTEGRATION & SECURITY
                  OBJECTIVE: Map digital profiles to physical biometric hardware.
                --}}
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Device User ID <span class="text-danger">*</span></label>
                        <input type="text" name="device_user_id" class="form-control @error('device_user_id') is-invalid @enderror" value="{{ old('device_user_id') }}" placeholder="e.g. 1001" required>
                        @error('device_user_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Initial Password <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Minimum 6 characters required" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <hr>

                {{-- PHASE 6: TRANSACTION FINALIZATION --}}
                <div class="d-flex justify-content-end gap-2 mt-3">
                    <button type="reset" class="btn btn-light">Reset Form</button>
                    <button type="submit" class="btn btn-dark">Create User</button>
                </div>
            </form>
        </div>
    </div>

    {{-- 
      PHASE 7: INTERFACE BEHAVIORAL LOGIC (JS)
      OBJECTIVE: Manage the conditional visibility of organizational input fields.
    --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const departmentSelect = document.getElementById('department_id');
            const newDeptWrapper = document.getElementById('new_department_wrapper');
            const newDeptInput = document.getElementById('new_department_name');
            const resetButton = document.querySelector('button[type="reset"]');

            if(departmentSelect) {
                departmentSelect.addEventListener('change', function() {
                    if (this.value === 'new') {
                        newDeptWrapper.classList.remove('d-none');
                        newDeptInput.setAttribute('required', 'required');
                        newDeptInput.focus();
                    } else {
                        newDeptWrapper.classList.add('d-none');
                        newDeptInput.removeAttribute('required');
                        newDeptInput.value = '';
                    }
                });
            }

            if(resetButton) {
                resetButton.addEventListener('click', function() {
                    newDeptWrapper.classList.add('d-none');
                    newDeptInput.removeAttribute('required');
                    newDeptInput.value = '';
                });
            }
        });
    </script>
@endsection