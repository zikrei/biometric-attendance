@extends('layouts.app')

@section('title', 'Submit Discrepancy')
@section('page_title', 'Submit Discrepancy')
@section('page_subtitle', 'Explain attendance issues and upload supporting document')

@section('sidebar')
    <a href="#">Dashboard</a>
    <a href="#">Profile</a>
    <a href="#">My Attendance</a>
    <a href="#" class="active">Discrepancies</a>
    <a href="#">Monthly Reports</a>
@endsection

@section('content')
<div class="card card-stat">
    <div class="card-body">
        <form enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Attendance Date</label>
                <input type="date" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Discrepancy Type</label>
                <select class="form-select">
                    <option>Insufficient Hours</option>
                    <option>Absent</option>
                    <option>Missed Clock In</option>
                    <option>Missed Clock Out</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Reason / Explanation</label>
                <textarea class="form-control" rows="4" placeholder="Enter your explanation"></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Supporting Document</label>
                <input type="file" class="form-control">
            </div>

            <button class="btn btn-dark">Submit</button>
        </form>
    </div>
</div>
@endsection