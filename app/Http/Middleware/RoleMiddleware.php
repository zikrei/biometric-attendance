<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request and enforce role-based access control (RBAC).
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        /**
         * PHASE 1: AUTHENTICATION VERIFICATION
         * OBJECTIVE: Ensure the inbound request originates from a valid, authenticated session.
         * PROCEDURES: Checks 'Auth::check()'; if the user is not logged in, the request is redirected to the login interface.
         */
        if (!Auth::check()) {
            return redirect('/login');
        }

        /**
         * DIAGNOSTIC UTILITY (DEVELOPMENT ONLY)
         * OBJECTIVE: Provide a snapshot of current authentication and role metadata for debugging.
         * METRICS: Includes Email, Role ID, Relationship Data, and the current permission requirements.
         */
        /*
        dd([
            '1. User Email' => Auth::user()->email,
            '2. Database Role ID' => Auth::user()->role_id,
            '3. Role Relationship Data' => Auth::user()->role,
            '4. Allowed Roles for this page' => $roles,
            '5. Actual User Role Name' => Auth::user()->role?->name,
        ]);
        */

        /**
         * PHASE 2: ROLE EXTRACTION & DATA NORMALIZATION
         * OBJECTIVE: Retrieve the user's role identity and standardize the input role array[cite: 14].
         * PROCEDURES: 
         * - Employs null-safe navigation to safely extract the 'name' property from the role relationship[cite: 14].
         * - Detects and parses pipe-delimited strings (e.g., 'Admin|HOD') passed from the routing layer[cite: 14].
         */
        $userRole = Auth::user()->role?->name;

        if (count($roles) === 1 && str_contains($roles[0], '|')) {
            $roles = explode('|', $roles[0]);
        }

        /**
         * PHASE 3: AUTHORIZATION EVALUATION
         * OBJECTIVE: Compare the user's active role against the defined permissions for the target route[cite: 14].
         * PROCEDURES: Uses 'in_array' to determine if the normalized role exists within the authorized list[cite: 14].
         */
        if (in_array($userRole, $roles)) {
            return $next($request); 
        }

        /**
         * PHASE 4: ACCESS DENIAL & EXCEPTION HANDLING
         * OBJECTIVE: Terminate unauthorized requests and provide clear feedback to the client[cite: 14].
         * OUTCOME: Triggers a 403 HTTP Exception with a custom unauthorized access message[cite: 14].
         */
        abort(403, 'Unauthorized Access. Your role does not have permission to view this page.');
    }
}