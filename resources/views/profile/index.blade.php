@extends('layouts.app')

@section('title', 'Profile')
@section('page_title', 'My Profile')
@section('page_subtitle', 'Update your personal information')

@section('sidebar')
    <a href="#">Dashboard</a>
    <a href="#" class="active">Profile</a>
    <a href="#">My Attendance</a>
    <a href="#">Discrepancies</a>
    <a href="#">Monthly Reports</a>
@endsection

@section('content')
<div class="card card-stat">
    <div class="card-body">
        <form>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Full Name</label>
                    <input type="text" class="form-control" value="Demo User">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" value="demo@email.com">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Department</label>
                    <input type="text" class="form-control" value="IT Department">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Role</label>
                    <input type="text" class="form-control" value="User">
                </div>
            </div>
            <button class="btn btn-dark">Save Changes</button>
        </form>
    </div>
</div>
@endsection