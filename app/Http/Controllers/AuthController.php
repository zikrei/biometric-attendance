<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Display the authentication entry point.
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Execute user authentication and session establishment.
     */
    public function login(Request $request)
    {
        /**
         * PHASE 1: CREDENTIAL VALIDATION & INPUT SANITIZATION
         * OBJECTIVE: Ensure the inbound request contains a properly formatted email and a password string.
         * PROCEDURES: Executes a strict validation check; fails back to the login page if criteria are not met.
         */
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        /**
         * PHASE 2: AUTHENTICATION ATTEMPT & PERSISTENCE CONFIGURATION
         * OBJECTIVE: Verify user identity against the database while respecting user session preferences.
         * PROCEDURES: 
         * - Detects the 'remember' checkbox state to toggle long-term cookie persistence.
         * - Executes 'Auth::attempt' to match credentials; returns an error response on failure.
         */
        $remember = $request->has('remember');

        if (!Auth::attempt($credentials, $remember)) {
            return back()->withErrors([
                'email' => 'Invalid email or password.',
            ])->onlyInput('email');
        }

        /**
         * PHASE 3: SECURITY HARDENING (SESSION INTEGRITY)
         * OBJECTIVE: Mitigate the risk of "Session Fixation" attacks during the privilege escalation process.
         * PROCEDURES: Forces the regeneration of the session ID immediately following a successful login.
         */
        $request->session()->regenerate();

        /**
         * PHASE 4: DYNAMIC ROLE-BASED REDIRECTION
         * OBJECTIVE: Route the authenticated user to their specific functional dashboard based on assigned permissions.
         * LOGIC: 
         * - Employs null-safe navigation (?->) to prevent system crashes for users without assigned roles.
         * - Routes 'Admin', 'HOD', and 'Integrity' roles to specialized dashboards; defaults others to the standard dashboard.
         */
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

        return redirect()->route('dashboard');
    }

    /**
     * Terminate the user session and clear authentication tokens.
     */
    public function logout(Request $request)
    {
        /**
         * PHASE 1: SESSION DESTRUCTION & TOKEN REFRESH
         * OBJECTIVE: Ensure a clean exit and prevent subsequent unauthorized access to the session data.
         * PROCEDURES: 
         * - Triggers a logout event, invalidates the existing session, and regenerates the CSRF token for the next visitor.
         * - Redirects the user back to the primary login interface.
         */
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}