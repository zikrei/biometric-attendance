<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    // Show all users
    public function index()
    {
        return view('admin.users.index');
    }

    // Show form to create new user
    public function create()
    {
        return view('admin.users.create');
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
        return view('admin.users.edit', compact('id'));
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