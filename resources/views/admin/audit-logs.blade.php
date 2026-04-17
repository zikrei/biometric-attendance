@extends('layouts.app')

@section('title', 'Audit Logs')
@section('page_title', 'Audit Logs')
@section('page_subtitle', 'Track important system actions')

@section('sidebar')
    <a href="#">Dashboard</a>
    <a href="#">User Management</a>
    <a href="#" class="active">Audit Logs</a>
    <a href="#">System Reports</a>
@endsection

@section('content')
<div class="card card-stat">
    <div class="card-body">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Date Time</th>
                    <th>User</th>
                    <th>Action</th>
                    <th>Description</th>
                    <th>IP Address</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>2026-04-12 09:15</td>
                    <td>Admin</td>
                    <td>Create User</td>
                    <td>Created staff account for Nurul</td>
                    <td>127.0.0.1</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection