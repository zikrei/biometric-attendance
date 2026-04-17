@extends('layouts.app')

@section('title', 'User Management')
@section('page_title', 'User Management')
@section('page_subtitle', 'Manage user accounts and roles')

@section('sidebar')
    <a href="#">Dashboard</a>
    <a href="#" class="active">User Management</a>
    <a href="#">Departments</a>
    <a href="#">Audit Logs</a>
    <a href="#">System Reports</a>
@endsection

@section('content')
<div class="card card-stat">
    <div class="card-body">
        <div class="d-flex justify-content-between mb-3">
            <h5 class="mb-0">Users</h5>
            <a href="#" class="btn btn-dark">+ Add User</a>
        </div>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Department</th>
                    <th>Role</th>
                    <th width="180">Action</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Ahmad</td>
                    <td>ahmad@email.com</td>
                    <td>IT</td>
                    <td>HOD</td>
                    <td>
                        <a href="#" class="btn btn-sm btn-outline-primary">Edit</a>
                        <a href="#" class="btn btn-sm btn-outline-danger">Delete</a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection