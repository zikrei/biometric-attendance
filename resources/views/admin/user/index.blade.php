@extends('layouts.app')

@section('title', 'User Management')

@section('page_title', 'Manage Users')

@section('page_subtitle', 'View, create, edit, or delete users.')

@section('content')
    <div class="d-flex justify-content-between mb-4">
        <h5 class="mb-0">Users List</h5>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">Add User</a>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body">
            <table class="table table-striped table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if($user->role == 'admin')
                                    <span class="badge bg-primary">🔵 Admin</span>
                                @elseif($user->role == 'hod')
                                    <span class="badge bg-warning">🟡 HOD</span>
                                @elseif($user->role == 'user')
                                    <span class="badge bg-success">🟢 User</span>
                                @elseif($user->role == 'integrity')
                                    <span class="badge bg-purple">🟣 Integrity Unit</span>
                                @endif
                            </td>
                            <td>
                                @if($user->status == 'active')
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection