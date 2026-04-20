<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;

class PasswordResetController extends Controller
{
    // 1. Show the form to request a reset link
    public function request()
    {
        return view('auth.forgot-password');
    }

    // 2. Handle the form submission and send the email
    public function email(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        $status = Password::sendResetLink($request->only('email'));

        return back()->with('success', 'If this email exists, a reset link has been sent!');
    }

    // 3. Show the actual password reset form (when they click the link)
    public function reset($token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    // 4. Save the new password to the database
    public function update(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed', // Requires a password_confirmation field in the HTML
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