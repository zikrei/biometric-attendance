@extends('layouts.app')

@section('title', 'My Attendance')
@section('page_title', 'My Attendance')
@section('page_subtitle', 'View your daily biometric logs')

@section('sidebar')
    <a href="#">Dashboard</a>
    <a href="#">Profile</a>
    <a href="#" class="active">My Attendance</a>
    <a href="#">Discrepancies</a>
    <a href="#">Monthly Reports</a>
@endsection

@section('content')
<div class="card card-stat">
    <div class="card-body">
        <div class="d-flex justify-content-between mb-3">
            <input type="month" class="form-control w-auto">
            <button class="btn btn-outline-dark">Print Report</button>
        </div>

        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Clock In</th>
                    <th>Clock Out</th>
                    <th>Total Hours</th>
                    <th>Status</th>
                    <th>Remark</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>2026-04-10</td>
                    <td>08:05</td>
                    <td>17:10</td>
                    <td>9.08</td>
                    <td><span class="badge bg-success">Present</span></td>
                    <td>-</td>
                </tr>
                <tr>
                    <td>2026-04-11</td>
                    <td>08:20</td>
                    <td>15:00</td>
                    <td>6.67</td>
                    <td><span class="badge bg-danger">Discrepancy</span></td>
                    <td><a href="#">Submit Explanation</a></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection