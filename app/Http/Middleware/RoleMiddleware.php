<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Show the login form
    public function showLogin()
    {
        return view('auth.login');
    }

    // Handle the login logic
    public function login(Request $request)
    {
        // Validate the login form data
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        // Attempt to log the user in
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            // Redirect based on role
            return redirect()->intended($this->redirectTo());
        }

        // If authentication fails, redirect back with an error message
        return back()->withErrors(['email' => 'Invalid email or password.']);
    }

    // Log out the user
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }

    // Redirect the user based on their role
    protected function redirectTo()
    {
        if (Auth::user()->role->name == 'admin') {
            return route('admin.dashboard');
        } elseif (Auth::user()->role->name == 'hod') {
            return route('hod.dashboard');
        } elseif (Auth::user()->role->name == 'integrity') {
            return route('integrity.dashboard');
        }
        return route('user.dashboard'); // Default to user dashboard
    }
}

