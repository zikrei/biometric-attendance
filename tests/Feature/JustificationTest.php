<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Role; // <--- Import the Role model
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class); 

test('users can submit a justification with a medical certificate', function () {
    // 1. Fake the storage
    Storage::fake('public'); 
    
    // 2. Create the Role FIRST
    Role::forceCreate(['id' => 1, 'name' => 'Staff']); 

    // 3. Create the user
    $user = User::factory()->create([
        'role_id' => 1,
    ]);
    
    $file = UploadedFile::fake()->image('medical_cert.jpg');

    // 4. Act as the user and submit the form
    $response = $this->actingAs($user)->post('/attendance', [
        'reason' => 'I was sick',
        'document' => $file,
    ]);

    // 5. Check that it successfully processed (302 Redirect is standard for form submissions)
    $response->assertStatus(302);
});