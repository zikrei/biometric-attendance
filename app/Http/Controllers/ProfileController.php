<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    // Show the profile edit form
    public function edit()
    {
        // Get the currently logged-in user
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    // Update the profile
    public function update(Request $request)
    {
        $user = Auth::user();

        // Validate the data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            // Allow them to keep their current email without triggering the "unique" error
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6', // Password is optional
        ]);

        // If they typed a new password, hash it. Otherwise, remove it from the array.
        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        // Save changes
        $user->update($validated);

        // Redirect back to the profile page with a success message
        return redirect()->route('profile.edit')->with('success', 'Profile updated successfully!');
    }
}