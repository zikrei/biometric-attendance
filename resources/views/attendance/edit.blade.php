@extends('layouts.app')

@section('title', 'Update Attendance Discrepancy')

@section('page_title', 'Attendance Discrepancy Request')
@section('page_subtitle', 'Provide a justification and supporting documents for attendance time adjustments or absences.')

@section('content')
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white border-0">
            <h5 class="mb-0">Update Attendance Adjustment Request</h5>
        </div>
        <div class="card-body">
            {{-- CRITICAL: Added enctype="multipart/form-data" for file uploads --}}
            <form action="{{ route('attendance.update', $attendance->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="clock_in" class="form-label fw-bold">Check-In Time</label>
                        <input type="time" name="clock_in" id="clock_in" class="form-control" value="{{ old('clock_in', $attendance->clock_in ?? $attendance->clockIn) }}">
                    </div>
                    <div class="col-md-6">
                        <label for="clock_out" class="form-label fw-bold">Check-Out Time</label>
                        <input type="time" name="clock_out" id="clock_out" class="form-control" value="{{ old('clock_out', $attendance->clock_out ?? $attendance->clockOut) }}">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="reason" class="form-label fw-bold">Reason for Adjustment</label>
                    <textarea name="reason" id="reason" class="form-control @error('reason') is-invalid @enderror" rows="4" placeholder="e.g., Medical Leave, Annual Leave, Missed check-in" required>{{ old('reason', $attendance->reason) }}</textarea>
                    @error('reason')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- NEW: File Upload Section --}}
                <div class="mb-4">
                    <label for="attachment" class="form-label fw-bold">Supporting Document (Optional)</label>
                    <input type="file" name="attachment" id="attachment" class="form-control @error('attachment') is-invalid @enderror" accept=".pdf,.jpg,.jpeg,.png">
                    <div class="form-text">Upload a Medical Certificate (MC) or other supporting document. Maximum file size: 10MB (PDF, JPG, PNG).</div>
                    @error('attachment')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    
                    {{-- Show a link if they already uploaded an attachment previously --}}
                    @if($attendance->attachment)
                        <div class="mt-3 p-3 bg-light rounded border">
                            <i class="bi bi-paperclip"></i> Existing Attachment: 
                            <a href="{{ asset('storage/' . $attendance->attachment) }}" target="_blank" class="fw-bold text-decoration-none">View Attachment</a>
                        </div>
                    @endif
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('attendance.list') }}" class="btn btn-outline-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update Request</button>
                </div>
            </form>
        </div>
    </div>
@endsection