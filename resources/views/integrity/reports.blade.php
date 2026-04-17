@extends('layouts.app')

@section('title', 'Final Reports')
@section('page_title', 'Finalized Reports')
@section('page_subtitle', 'Read-only review for compliance and audit')

@section('sidebar')
    <a href="#" class="active">Final Reports</a>
    <a href="#">Export Reports</a>
@endsection

@section('content')
<div class="card card-stat">
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-3"><input type="text" class="form-control" placeholder="Department"></div>
            <div class="col-md-3"><input type="date" class="form-control"></div>
            <div class="col-md-3"><input type="date" class="form-control"></div>
            <div class="col-md-3"><button class="btn btn-dark w-100">Export CSV</button></div>
        </div>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Staff</th>
                    <th>Department</th>
                    <th>Month</th>
                    <th>Status</th>
                    <th>HOD Remark</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Aina</td>
                    <td>IT</td>
                    <td>April 2026</td>
                    <td><span class="badge bg-success">Verified</span></td>
                    <td>Approved with supporting document.</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection