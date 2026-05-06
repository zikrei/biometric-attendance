<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Retrieve the current user's identity and initialize the modification interface.
     */
    public function edit()
    {
        /**
         * PHASE 1: CONTEXTUAL IDENTITY RETRIEVAL
         * OBJECTIVE: Access the active session data for the authenticated user.
         * PROCEDURES: Fetches the user model instance via the Auth facade to populate the edit form.
         */
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    /**
     * Process profile modifications and manage sensitive data updates.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        /**
         * PHASE 1: DATA INTEGRITY & CONSTRAINT VALIDATION
         * OBJECTIVE: Sanitize inbound profile modifications and enforce organizational standards.
         * PROCEDURES: 
         * - Validates name and email formatting.
         * - Logic: Employs an 'ignore' constraint on the unique email check to allow users to save their current email without triggering a conflict error.
         */
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6', 
        ]);

        /**
         * PHASE 2: CONDITIONAL CRYPTOGRAPHIC PROCESSING
         * OBJECTIVE: Securely handle password changes while preventing accidental data loss.
         * PROCEDURES: 
         * - Evaluates if the 'password' field is populated.
         * - If present, the new value is processed via 'Hash::make'.
         * - If absent, the field is removed from the update array to preserve the existing hashed password in the database.
         */
        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        /**
         * PHASE 3: PERSISTENCE & USER FEEDBACK
         * OBJECTIVE: Commit changes to the primary data store and provide immediate transaction confirmation.
         * PROCEDURES: 
         * - Executes the 'update' method on the User model with the sanitized array.
         * - Redirects the user with a session-based success notification.
         */
        $user->update($validated);

        return redirect()->route('profile.edit')->with('success', 'Profile updated successfully!');
    }
}