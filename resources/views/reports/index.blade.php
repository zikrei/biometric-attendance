@extends('layouts.app')

@section('title', 'Attendance Report')
@section('page_title', 'Attendance Report')
@section('page_subtitle', 'Generate and view attendance reports.')

@section('content')
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4">
        <h5 class="mb-4">Generate Attendance Report</h5>
        
        <form action="{{ url('/admin/reports/generate') }}" method="GET">
            <div class="row g-3 mb-4">
                {{-- 1. Month Picker --}}
                <div class="col-md-4">
                    <label class="form-label fw-bold">Select Month</label>
                    <input type="month" name="month" class="form-control" value="{{ date('Y-m') }}" required>
                </div>

                {{-- 2. Department Dropdown --}}
                <div class="col-md-4">
                    <label class="form-label fw-bold">Department (Optional)</label>
                    <select name="department_id" id="departmentSelect" class="form-select">
                        <option value="">All Departments</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- 3. Auto-sorting User Dropdown --}}
                <div class="col-md-4">
                    <label class="form-label fw-bold">User (Optional)</label>
                    <select name="user_id" id="userSelect" class="form-select">
                        <option value="">All Users</option>
                        @foreach($users as $user)
                            {{-- data-dept powers the JavaScript sorting! --}}
                            <option value="{{ $user->id }}" data-dept="{{ $user->department_id }}">
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">Generate Report</button>
            </div>
        </form>
    </div>
</div>

{{-- JavaScript to Auto-Sort Users based on Department --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deptSelect = document.getElementById('departmentSelect');
        const userSelect = document.getElementById('userSelect');
        
        // Save all original user options when the page first loads
        const allUserOptions = Array.from(userSelect.options);

        deptSelect.addEventListener('change', function() {
            const selectedDept = this.value;

            // Clear the user dropdown
            userSelect.innerHTML = '<option value="">All Users</option>';

            // Loop through our saved list of users
            allUserOptions.forEach(option => {
                if (option.value === '') {
                    userSelect.appendChild(option.cloneNode(true));
                    return; 
                }
                
                // If no department is selected, OR the user's department matches the selection, put them back!
                if (selectedDept === '' || option.getAttribute('data-dept') === selectedDept) {
                    userSelect.appendChild(option.cloneNode(true));
                }
            });
        });
    });
</script>
@endsection