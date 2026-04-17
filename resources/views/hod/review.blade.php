@extends('layouts.app')

@section('title', 'Review Discrepancy')
@section('page_title', 'Review Discrepancy')
@section('page_subtitle', 'Approve or reject staff explanation')

@section('sidebar')
    <a href="#">Dashboard</a>
    <a href="#">Department Attendance</a>
    <a href="#" class="active">Pending Discrepancies</a>
    <a href="#">Monthly Reports</a>
@endsection

@section('content')
<div class="card card-stat">
    <div class="card-body">
        <div class="mb-3">
            <strong>Staff Name:</strong> Aina
        </div>
        <div class="mb-3">
            <strong>Date:</strong> 2026-04-11
        </div>
        <div class="mb-3">
            <strong>Issue:</strong> Insufficient Hours
        </div>
        <div class="mb-3">
            <strong>Explanation:</strong>
            <p class="mb-0">I attended a medical appointment and returned late.</p>
        </div>
        <div class="mb-3">
            <strong>Supporting Document:</strong>
            <a href="#">View Attachment</a>
        </div>

        <div class="mb-3">
            <label class="form-label">HOD Remark</label>
            <textarea class="form-control" rows="3"></textarea>
        </div>

        <div class="d-flex gap-2">
            <button class="btn btn-success">Approve</button>
            <button class="btn btn-danger">Reject</button>
        </div>
    </div>
</div>
@endsection