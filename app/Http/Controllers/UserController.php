<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\Department;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a comprehensive registry of all system users.
     */
    public function index()
    {
        /**
         * PHASE 1: DATA AGGREGATION & RELATIONSHIP LOADING
         * OBJECTIVE: Retrieve a complete list of users while minimizing database overhead.
         * PROCEDURES: Eager loads 'role' and 'department' relationships to ensure all organizational metadata is available for the index view.
         */
        $users = User::with(['role', 'department'])->get();
        
        return view('admin.user.index', compact('users'));
    }

    /**
     * Initialize the interface for new user registration.
     */
    public function create()
    {
        /**
         * PHASE 1: METADATA PREPARATION
         * OBJECTIVE: Populate the registration form with valid organizational roles and departments.
         */
        $roles = Role::all();
        $departments = Department::all();
        
        return view('admin.user.create', compact('roles', 'departments'));
    }

    /**
     * Execute the registration of a new user and manage dynamic department creation.
     */
    public function store(Request $request)
    {
        /**
         * PHASE 1: PAYLOAD VALIDATION & CONDITIONAL LOGIC
         * OBJECTIVE: Sanitize inbound data and enforce record uniqueness.
         * CONSTRAINTS: 
         * - Ensures email is unique within the users table.
         * - Requires 'new_department_name' only if 'department_id' is set to "new".
         */
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role_id' => 'required|integer',
            'department_id' => 'required', 
            'new_department_name' => 'required_if:department_id,new|string|max:255|nullable', 
            'device_user_id' => 'required|string',
            'password' => 'required|string|min:6',
        ]);

        $departmentId = $request->department_id;

        /**
         * PHASE 2: DYNAMIC ENTITY RESOLUTION
         * OBJECTIVE: Accommodate the creation of a new department on-the-fly during user registration.
         * PROCEDURES: 
         * - Checks for the "new" flag in the department field.
         * - Persists the new department to the database and captures the generated ID for the user record.
         */
        if ($departmentId === 'new') {
            $department = Department::create([
                'name' => $request->new_department_name
            ]);
            
            $departmentId = $department->id; 
        }

        /**
         * PHASE 3: SECURE RECORD CREATION
         * OBJECTIVE: Persist the user profile with a cryptographically hashed password.
         * PROCEDURES: Maps the validated request data and the resolved department ID to a new User instance.
         */
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role_id' => $request->role_id,
            'department_id' => $departmentId, 
            'device_user_id' => $request->device_user_id,
            'password' => bcrypt($request->password),
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully!');
    }

    /**
     * Retrieve a specific user record for modification.
     */
    public function edit($id)
    {
        /**
         * PHASE 1: RECORD IDENTIFICATION & RESOURCE MAPPING
         * OBJECTIVE: Load a specific user profile and its associated selection metadata.
         */
        $user = User::findOrFail($id);
        $roles = Role::all();
        $departments = Department::all();
        
        return view('admin.user.edit', compact('user', 'roles', 'departments'));
    }

    /**
     * Update existing user details and manage credential security.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        /**
         * PHASE 1: STATE MODIFICATION VALIDATION
         * OBJECTIVE: Validate updates while allowing the user to retain their existing email.
         */
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6', 
            'role_id' => 'required|exists:roles,id',
            'department_id' => 'required|exists:departments,id',
            'status' => 'nullable|in:Active,Inactive'
        ]);

        /**
         * PHASE 2: CONDITIONAL SECURITY PROCESSING
         * OBJECTIVE: Protect existing credentials if a password update is not requested.
         * PROCEDURES: Hashes the new password if the field is filled; otherwise, removes the field to avoid overwriting current data.
         */
        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        /**
         * PHASE 3: PERSISTENCE FINALIZATION
         * OBJECTIVE: Commit all changes and return a success confirmation.
         */
        $user->update($validated);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully!');
    }

    /**
     * Terminate a user account and remove its database entry.
     */
    public function destroy($id)
    {
        /**
         * PHASE 1: RECORD DELETION & RESOURCE CLEANUP
         * OBJECTIVE: Permanently remove a user from the system.
         */
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully!');
    }

    /**
     * Compile a formatted user directory for print production.
     */
    public function print()
    {
        /**
         * PHASE 1: REPORTING DATA COMPILATION
         * OBJECTIVE: Retrieve a sorted dataset of users for external reporting.
         * PROCEDURES: Orders users alphabetically by name and includes all role/department relationships.
         */
        $users = \App\Models\User::with(['role', 'department'])->orderBy('name')->get();
        
        return view('admin.user.print', compact('users'));
    }
}