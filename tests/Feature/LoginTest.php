<?php

use App\Models\User;
use App\Models\Role; // <--- Import the Role model
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('user can login successfully and is authenticated', function () {
    // 1. Create the Role in the empty testing database FIRST
    Role::forceCreate(['id' => 1, 'name' => 'Staff']); 

    // 2. Now we can safely create a user and assign them to that role!
    $user = User::factory()->create([
        'password' => bcrypt('password123'),
        'role_id' => 1,
    ]);

    // 3. Simulate the user filling out the login form and clicking "Sign In"
    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'password123',
    ]);

    // 4. Verify the system actually logged them in securely
    $this->assertAuthenticatedAs($user);

    // 5. Verify they were redirected away from the login page
    $response->assertStatus(302);
});