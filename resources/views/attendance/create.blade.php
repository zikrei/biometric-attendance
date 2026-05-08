@extends('layouts.app')

@section('title', 'Submit Attendance Discrepancy')

{{-- 
  PHASE 1: CONTEXTUAL HEADER & METADATA
  OBJECTIVE: Establish the temporal context for the discrepancy and provide an overview of the submission requirements.
  PROCEDURE: Parses the $date variable using Carbon to provide a user-friendly reference for the specific missing record.
--}}
@section('page_title', 'Submit Discrepancy')
@section('page_subtitle', 'Provide a reason and supporting documents for your missing attendance record.')

@section('content')
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    
                    {{-- 
                      PHASE 2: SECURE MULTIPART TRANSACTION INITIATION
                      OBJECTIVE: Define the submission endpoint and enable binary data transmission for file uploads.
                      PROCEDURE: Employs 'multipart/form-data' to accommodate supporting documentation attachments.
                    --}}
                    <form action="{{ route('attendance.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        {{-- Hidden field to pass the date safely to the controller --}}
                        <input type="hidden" name="date" value="{{ $date }}">

                        <div class="mb-4">
                            <h5 class="fw-bold border-bottom pb-2">Record Details for {{ \Carbon\Carbon::parse($date)->format('F d, Y (l)') }}</h5>
                        </div>

                        {{-- 
                          PHASE 3: TEMPORAL DATA ADJUSTMENT
                          OBJECTIVE: Capture estimated or corrected clock times for the discrepancy date.
                          FLEXIBILITY: Allows for partial entries (e.g., missed clock-out) or complete absence reporting.
                        --}}
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Clock-In Time (Optional)</label>
                                <input type="time" name="clock_in" class="form-control" placeholder="--:--">
                                <small class="text-muted">Leave blank if completely absent.</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Clock-Out Time (Optional)</label>
                                <input type="time" name="clock_out" class="form-control" placeholder="--:--">
                            </div>
                        </div>

                        {{-- 
                          PHASE 4: NARRATIVE JUSTIFICATION
                          OBJECTIVE: Require the employee to provide a written rationale for the missing biometric log.
                          CONSTRAINTS: Enforced as a mandatory field ('required') to ensure HODs have sufficient context for review.
                        --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Reason for Discrepancy <span class="text-danger">*</span></label>
                            <textarea name="reason" rows="4" class="form-control" required placeholder="Please explain why there is no attendance record for this date..."></textarea>
                        </div>

                        {{-- 
                          PHASE 5: EVIDENTIARY SUPPORT (FILE UPLOAD)
                          OBJECTIVE: Allow for the attachment of formal documentation (e.g., medical certificates, off-site duty orders).
                          SPECIFICATIONS: Restricts formats to PDF or standard images with a 10MB threshold for server performance.
                        --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold">Supporting Document (Optional)</label>
                            <input type="file" name="attachment" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                            <small class="text-muted">Accepted formats: PDF, JPG, PNG (Max: 10MB)</small>
                        </div>

                        {{-- 
                          PHASE 6: WORKFLOW FINALIZATION
                          OBJECTIVE: Commit the justification to the vetting queue or safely abort the transaction.
                        --}}
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('attendance.list') }}" class="btn btn-outline-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Submit Discrepancy</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection