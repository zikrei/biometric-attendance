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
     * PHASE 1: DATA AGGREGATION & PAGINATION
     * OBJECTIVE: Retrieve a segmented registry of users to optimize dashboard performance.
     * PROCEDURES: Eager loads 'role' and 'department' to prevent N+1 queries and limits output to 10 records per page.
     */
    public function index()
    {
        $users = User::with(['role', 'department'])->paginate(8);
        
        // Switched to singular 'user' to match your folder structure
        return view('admin.user.index', compact('users'));
    }

    /**
     * PHASE 1: METADATA PREPARATION
     * OBJECTIVE: Populate the registration interface with organizational units and authorization tiers.
     */
    public function create()
    {
        $roles = Role::all();
        $departments = Department::all();
        
        return view('admin.user.create', compact('roles', 'departments'));
    }

    /**
     * PHASE 1: PAYLOAD VALIDATION & CONDITIONAL LOGIC
     * OBJECTIVE: Sanitize inbound data and enforce record uniqueness across the system.
     * PHASE 2: DYNAMIC ENTITY RESOLUTION
     * OBJECTIVE: Support on-the-fly department creation if the "New Department" flag is detected.
     * PHASE 3: SECURE RECORD CREATION
     * OBJECTIVE: Persist the new profile with encrypted credentials and hardware mapping.
     */
    public function store(Request $request)
    {
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

        if ($departmentId === 'new') {
            $department = Department::create(['name' => $request->new_department_name]);
            $departmentId = $department->id; 
        }

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
     * PHASE 1: RECORD IDENTIFICATION & RESOURCE MAPPING
     * OBJECTIVE: Load a specific user profile and its associated selection metadata for editing.
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all();
        $departments = Department::all();
        
        return view('admin.user.edit', compact('user', 'roles', 'departments'));
    }

    /**
     * PHASE 1: STATE MODIFICATION VALIDATION
     * OBJECTIVE: Validate updates while allowing the user to maintain their current email address.
     * PHASE 2: CONDITIONAL SECURITY PROCESSING
     * OBJECTIVE: Update the password only if a new value is explicitly provided in the request.
     * PHASE 3: PERSISTENCE FINALIZATION
     * OBJECTIVE: Commit all validated updates to the database record.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6', 
            'role_id' => 'required|exists:roles,id',
            'department_id' => 'required|exists:departments,id',
            'status' => 'nullable|in:Active,Inactive'
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully!');
    }

    /**
     * PHASE 1: RECORD DELETION & RESOURCE CLEANUP
     * OBJECTIVE: Permanently remove a user from the central system registry.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully!');
    }

    /**
     * PHASE 1: REPORTING DATA COMPILATION
     * OBJECTIVE: Compile a full, sorted dataset for high-quality print production.
     */
    public function print()
    {
        $users = User::with(['role', 'department'])->orderBy('name')->get();
        
        return view('admin.user.print', compact('users'));
    }
}