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
        // Notice the email rule: it allows the user to keep their current email without triggering the "unique" error!
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6', // Nullable means it's optional!
            'role_id' => 'required|exists:roles,id',
            'department_id' => 'required|exists:departments,id',
            'status' => 'nullable|in:active,inactive' // Allow status updates
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
}