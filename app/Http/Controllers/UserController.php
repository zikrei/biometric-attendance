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
        // 1. Add device_user_id to the validation rules
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'role_id' => 'required|exists:roles,id',
            'department_id' => 'required|exists:departments,id',
            'device_user_id' => 'required|string|unique:users,device_user_id', // <-- Added validation (Ensures no duplicates)
            'password' => 'required|string|min:6',
        ]);

        // 2. Include it when creating the User
        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role_id' => $validated['role_id'],
            'department_id' => $validated['department_id'],
            'device_user_id' => $validated['device_user_id'], // <-- SAVE TO DATABASE
            'password' => \Illuminate\Support\Facades\Hash::make($validated['password']),
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    // Show form to edit user
    public function edit($id)
    {
        // 1. Find the exact user by their ID
        $user = User::findOrFail($id);
        
        // 2. Fetch roles and departments for the dropdown menus
        $roles = Role::all();
        $departments = Department::all();
        
        // 3. Send all this data to the edit view (Notice the singular 'admin.user.edit'!)
        return view('admin.user.edit', compact('user', 'roles', 'departments'));
    }

// Update user details
    public function update(Request $request, $id)
    {
        // 1. Find the user we are updating
        $user = User::findOrFail($id);

        // 2. Validate the incoming data
    
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6', // Nullable means it's optional!
            'role_id' => 'required|exists:roles,id',
            'department_id' => 'required|exists:departments,id',
            'status' => 'nullable|in:Active,Inactive'
        ]);

        // 3. Handle the password securely
        if ($request->filled('password')) {
            // If they typed a new password, encrypt it
            $validated['password'] = Hash::make($validated['password']);
        } else {
            // If they left it blank, remove it from the array so we don't overwrite the old one
            unset($validated['password']);
        }

        // 4. Save the updates to the database
        $user->update($validated);

        // 5. Redirect back with a success message
        return redirect()->route('admin.users.index')->with('success', 'User updated successfully!');
    }

    // Delete user (Placeholder for now)
    public function destroy($id)
{
        // 1. Find the exact user in the database
        $user = User::findOrFail($id);

        // 2. Delete the user
        $user->delete();

        // 3. Redirect back to the table with a success message
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully!');
    }

    // Print all users list
    public function print()
    {
        // Fetch all users with their roles and departments, sorted alphabetically
        $users = \App\Models\User::with(['role', 'department'])->orderBy('name')->get();
        
        return view('admin.user.print', compact('users'));
    }
}