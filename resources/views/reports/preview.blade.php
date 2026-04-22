@extends('layouts.app')

@section('title', 'Attendance Report')
@section('page_title', 'Department Attendance Reports')
@section('page_subtitle', 'Generate, preview, and export monthly attendance summaries for your staff.')

@section('content')

    {{-- FILTER FORM SECTION --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4 filter-section no-print">
        <div class="card-body p-4">
            {{-- <h2 class="mb-4">Department Attendance Report</h2>
            <p class="text-muted">Generate, preview, and export monthly attendance summaries for your staff.</p> --}}
            <form action="{{ url('/admin/reports/generate') }}" method="GET">
                <div class="row g-3 mb-4">
                    
                    @php $isHOD = auth()->user()->role?->name === 'HOD'; @endphp

                    {{-- 1. Month Picker (Expands if HOD) --}}
                    <div class="{{ $isHOD ? 'col-md-6' : 'col-md-4' }}">
                        <label class="form-label fw-bold">Choose Month</label>
                        <input type="month" name="month" class="form-control" value="{{ request('month', date('Y-m')) }}" required>
                    </div>

                    {{-- 2. Department Dropdown (HIDDEN ENTIRELY FOR HODs) --}}
                    @if(!$isHOD)
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Select Department (Optional)</label>
                            <select name="department_id" id="departmentSelect" class="form-select">
                                <option value="">All Departments</option>
                                @if(isset($departments))
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                                            {{ $dept->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    @endif

                    {{-- 3. Auto-sorting User Dropdown (Expands if HOD) --}}
                    <div class="{{ $isHOD ? 'col-md-6' : 'col-md-4' }}">
                        <label class="form-label fw-bold">Select User (Optional)</label>
                        <select name="user_id" id="userSelect" class="form-select">
                            <option value="">All Users</option>
                            @if(isset($users))
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" data-dept="{{ $user->department_id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-dark">Generate Preview</button>
                </div>
            </form>
        </div>
    </div>

    {{-- REPORT PREVIEW SECTION --}}
    <div class="card border-0 shadow-sm rounded-4 mt-3">
        <div class="card-body p-4">
            <div class="mb-5">
                <div class="text-center mb-4">
                    <h2 class="fw-bold mb-2">Department Attendance Report</h2>
                    <h5 class="text-muted">
                        {{ $department->name ?? 'All Departments' }} - {{ \Carbon\Carbon::parse($monthInput)->format('F Y') }}
                    </h5>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered mt-4 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Name</th>
                                <th>Department</th>
                                <th>Date</th>
                                <th>Check-In</th>
                                <th>Check-Out</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($attendances as $attendance)
                                <tr>
                                    <td>{{ $attendance->user->name }}</td>
                                    <td>{{ $attendance->user->department?->name ?? 'N/A' }}</td>
                                    <td>{{ $attendance->date }}</td>
                                    <td>{{ $attendance->clock_in ?? '--:--' }}</td>
                                    <td>{{ $attendance->clock_out ?? '--:--' }}</td>
                                    <td>
                                        @if(strtolower($attendance->status) == 'pending')
                                            <span class="badge bg-warning text-dark border">🟡 Awaiting Approval</span>
                                        @elseif(strtolower($attendance->status) == 'approved')
                                            <span class="badge bg-success border">🟢 Approved</span>
                                        @elseif(strtolower($attendance->status) == 'rejected')
                                            <span class="badge bg-danger border">🔴 Rejected</span>
                                        @else
                                            <span class="badge bg-secondary border">{{ ucfirst($attendance->status) }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">No attendance records available for the selected criteria.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- ACTION BUTTONS --}}
            <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top no-print">
                <a href="{{ route('reports.print', request()->query()) }}" target="_blank" class="btn btn-primary px-4">
                    <i class="bi bi-printer me-2"></i> Print Individual Staff Reports
                </a>
            </div>

        </div>
    </div>

    {{-- JavaScript to Auto-Sort Users based on Department --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deptSelect = document.getElementById('departmentSelect');
            const userSelect = document.getElementById('userSelect');
            
            // If the user is an HOD, skip this script!
            if (!deptSelect) return; 

            const allUserOptions = Array.from(userSelect.options);

            function filterUsers() {
                const selectedDept = deptSelect.value;
                const previouslySelectedUser = userSelect.value; 

                userSelect.innerHTML = '<option value="">All Users</option>';
                
                allUserOptions.forEach(option => {
                    if (option.value === '') return; 
                    
                    if (selectedDept === '' || option.getAttribute('data-dept') === selectedDept) {
                        const newOption = option.cloneNode(true);
                        if (newOption.value === previouslySelectedUser) {
                            newOption.selected = true;
                        }
                        userSelect.appendChild(newOption);
                    }
                });
            }

            filterUsers();

            deptSelect.addEventListener('change', function() {
                userSelect.value = ''; 
                filterUsers();
            });
        });
    </script>
@endsection