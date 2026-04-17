<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // 1. If not logged in, send to login
        if (!Auth::check()) {
            return redirect('/login');
        }

        // ---> DIAGNOSTIC BLOCK: This will freeze the page and print your data <---
        // dd([
        //     '1. User Email' => Auth::user()->email,
        //     '2. Database Role ID' => Auth::user()->role_id,
        //     '3. Role Relationship Data' => Auth::user()->role,
        //     '4. Allowed Roles for this page' => $roles,
        //     '5. Actual User Role Name' => Auth::user()->role?->name,
        // ]);
        // ------------------------------------------------------------------------

        // 2. Get the current user's role name safely
        $userRole = Auth::user()->role?->name;

        // 3. Handle the 'Role1|Role2' pipe format from web.php
        if (count($roles) === 1 && str_contains($roles[0], '|')) {
            $roles = explode('|', $roles[0]);
        }

        // 4. Check if the user's role is inside the allowed roles list
        if (in_array($userRole, $roles)) {
            return $next($request); // Allowed! Proceed to the page.
        }

        // 5. If they don't match, show an Access Denied error
        abort(403, 'Unauthorized Access. Your role does not have permission to view this page.');
    }
}