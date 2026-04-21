@extends('layouts.app')

@section('title', 'Generate Attendance Report')
@section('page_title', 'Attendance Report')
@section('page_subtitle', 'Generate and view detailed attendance reports for your selected criteria.')

@section('content')
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4">
        <h5 class="mb-4">Generate Attendance Report</h5>
        
        <form action="{{ url('/admin/reports/generate') }}" method="GET">
            <div class="row g-3 mb-4">
                
                {{-- Determine if the user is an HOD to hide the department box --}}
                @php $isHOD = auth()->user()->role?->name === 'HOD'; @endphp

                {{-- 1. Month Picker (Expands if HOD) --}}
                <div class="{{ $isHOD ? 'col-md-6' : 'col-md-4' }}">
                    <label class="form-label fw-bold">Choose Month</label>
                    <input type="month" name="month" class="form-control" value="{{ date('Y-m') }}" required>
                </div>

                {{-- 2. Department Dropdown (HIDDEN ENTIRELY FOR HODs) --}}
                @if(!$isHOD)
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Select Department (Optional)</label>
                        <select name="department_id" id="departmentSelect" class="form-select">
                            <option value="">All Departments</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                {{-- 3. Auto-sorting User Dropdown (Expands if HOD) --}}
                <div class="{{ $isHOD ? 'col-md-6' : 'col-md-4' }}">
                    <label class="form-label fw-bold">Select User (Optional)</label>
                    <select name="user_id" id="userSelect" class="form-select">
                        <option value="">All Users</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" data-dept="{{ $user->department_id }}">
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">Generate Attendance Report</button>
            </div>
        </form>
    </div>
</div>

{{-- JavaScript to Auto-Sort Users based on Department --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deptSelect = document.getElementById('departmentSelect');
        const userSelect = document.getElementById('userSelect');
        
        // If the user is an HOD, the department select doesn't exist, so we skip this script!
        if (!deptSelect) return; 

        const allUserOptions = Array.from(userSelect.options);

        deptSelect.addEventListener('change', function() {
            const selectedDept = this.value;
            userSelect.innerHTML = '<option value="">All Users</option>';
            
            allUserOptions.forEach(option => {
                if (option.value === '') {
                    userSelect.appendChild(option.cloneNode(true));
                    return; 
                }
                if (selectedDept === '' || option.getAttribute('data-dept') === selectedDept) {
                    userSelect.appendChild(option.cloneNode(true));
                }
            });
        });
    });
</script>
@endsection