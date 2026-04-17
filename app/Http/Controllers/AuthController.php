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
        // 1. Safely get the role name using the null-safe operator (?->)
        // If the user has no role, it returns null instead of crashing the app.
        $roleName = Auth::user()->role?->name;

        // 2. Match the exact capitalized strings we used in the RoleSeeder
        if ($roleName === 'admin') {
            return route('admin.dashboard');
        } elseif ($roleName === 'HOD') {
            return route('hod.dashboard');
        } elseif ($roleName === 'Integrity') {
            return route('integrity.dashboard');
        }
        
        // 3. Default fallback if they have the 'Staff' role, or no role at all
        return route('dashboard'); // Note: I changed this to 'dashboard' based on your web.php file earlier!
    }
}