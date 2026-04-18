<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (!Auth::attempt($credentials)) {
            return back()->withErrors([
                'email' => 'Invalid email or password.',
            ])->onlyInput('email');
        }

        // Protects against session fixation attacks
        $request->session()->regenerate();

        // The ?-> safely gets the role. If a user has no role, it returns null instead of crashing!
        $role = auth()->user()->role?->name;

        if ($role === 'Admin') {
            return redirect()->route('admin.dashboard');
        }

        if ($role === 'HOD') {
            return redirect()->route('hod.dashboard');
        }

        if ($role === 'Integrity') {
            return redirect()->route('integrity.dashboard'); 
        }

        // Default fallback for Staff, or users without an assigned role
        return redirect()->route('dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    
}