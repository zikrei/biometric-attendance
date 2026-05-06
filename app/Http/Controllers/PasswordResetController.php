<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;

class PasswordResetController extends Controller
{
    /**
     * PHASE 1: REQUEST INITIATION & VIEW RENDERING
     * OBJECTIVE: Provide the user with an interface to request a password recovery link.
     * PROCEDURES: Renders the 'auth.forgot-password' view to capture the user's registered email address.
     */
    public function request()
    {
        return view('auth.forgot-password');
    }

    /**
     * PHASE 2: IDENTITY VERIFICATION & DISPATCH
     * OBJECTIVE: Validate the existence of the email and trigger the recovery communication.
     * PROCEDURES:
     * - Validates that the input is a valid email and exists within the 'users' table.
     * - Utilizes the Laravel Password facade to generate and send a secure reset link.
     * FINALIZATION: Returns the user to the previous page with a generic success message to prevent email enumeration.
     */
    public function email(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        $status = Password::sendResetLink($request->only('email'));

        return back()->with('success', 'If this email exists, a reset link has been sent!');
    }

    /**
     * PHASE 3: SECURE TOKEN VALIDATION
     * OBJECTIVE: Verify the authenticity of the reset request through a unique URL token.
     * PROCEDURES: Captures the token from the inbound URL and passes it to the 'auth.reset-password' form for client-side processing.
     */
    public function reset($token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    /**
     * PHASE 4: CRYPTOGRAPHIC UPDATE & TRANSACTION FINALIZATION
     * OBJECTIVE: Execute the password change and terminate the recovery session.
     * DATA INTEGRITY:
     * - Validates that the token, email, and password (minimum 6 characters) meet system requirements.
     * - Requires 'password_confirmation' to ensure user accuracy.
     * PROCEDURES: 
     * - Resets the password using a closure to hash the new value via 'Hash::make'.
     * - Refreshes the 'Remember Token' to invalidate existing persistent sessions.
     * - Fires the 'PasswordReset' event for system-wide auditing.
     */
    public function update(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed', 
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));
                
                $user->save();
                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('success', 'Your password has been successfully reset! Please log in.');
        }

        return back()->withErrors(['email' => [__($status)]]);
    }
}