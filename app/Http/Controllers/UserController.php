<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; // <-- MUST add this so the controller knows what a User is!

class UserController extends Controller
{
    // Show all users
    public function index()
    {
        // Fetch all users with their roles and departments
        $users = User::with(['role', 'department'])->get();
        
        // Pass the $users variable into the view
        return view('admin.users.index', compact('users'));
    }

    // Show form to create new user
    public function create()
    {
        return "Create User Page (We will build the form view next!)";
    }

    // Store new user
    public function store(Request $request)
    {
        // Logic to store new user
        return redirect()->route('admin.users.index');
    }

    // Show form to edit user
    public function edit($id)
    {
        return "Edit User Page for User ID: $id (We will build the form view next!)";
    }

    // Update user details
    public function update(Request $request, $id)
    {
        // Logic to update user details
        return redirect()->route('admin.users.index');
    }

    // Delete user
    public function destroy($id)
    {
        // Logic to delete user
        return redirect()->route('admin.users.index');
    }
}