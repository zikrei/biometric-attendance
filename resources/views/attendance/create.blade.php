@extends('layouts.app')

@section('title', 'Create Attendance')

@section('page_title', 'Create New Attendance')

@section('page_subtitle', 'Submit your clock-in and clock-out times.')

@section('content')
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white border-0">
            <h5 class="mb-0">Create Attendance</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('attendance.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="clockIn" class="form-label">Clock In</label>
                    <input type="time" name="clockIn" id="clockIn" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="clockOut" class="form-label">Clock Out</label>
                    <input type="time" name="clockOut" id="clockOut" class="form-control" required>
                </div>
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">Submit Attendance</button>
                </div>
            </form>
        </div>
    </div>
@endsection