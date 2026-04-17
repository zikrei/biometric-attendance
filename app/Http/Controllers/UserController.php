<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\Department;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Show all users
    public function index()
    {
        // 1. Fetch all users from the database, including their roles and departments
        $users = User::with(['role', 'department'])->get();
        
        // 2. Send the $users variable to your new singular view path
        return view('admin.user.index', compact('users'));
    }

    // Show form to create new user
    public function create()
    {
        // Fetch roles and departments for the dropdown menus
        $roles = Role::all();
        $departments = Department::all();
        
        return view('admin.user.create', compact('roles', 'departments'));
    }

    // Store new user in the database
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'role_id' => 'required|exists:roles,id',
            'department_id' => 'required|exists:departments,id',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully!');
    }

    // Show form to edit user (Placeholder for now)
    public function edit($id)
    {
        return "Edit User Page for User ID: $id (We will build the form view next!)";
    }

    // Update user details (Placeholder for now)
    public function update(Request $request, $id)
    {
        return redirect()->route('admin.users.index');
    }

    // Delete user (Placeholder for now)
    public function destroy($id)
    {
        return redirect()->route('admin.users.index');
    }
}