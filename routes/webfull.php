<?php

use Illuminate\Support\Facades\Route;

// ================== AUTH ==================
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ================== DASHBOARD ==================
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');  // This will be dynamically handled by role-based views
    });

    // Admin Dashboard
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->middleware('role:admin');

    // HOD Dashboard
    Route::get('/hod/dashboard', function () {
        return view('hod.dashboard');
    })->middleware('role:hod');

    // User Dashboard
    Route::get('/user/dashboard', function () {
        return view('user.dashboard');
    })->middleware('role:user');

    // Integrity Unit Dashboard
    Route::get('/integrity/dashboard', function () {
        return view('integrity.dashboard');
    })->middleware('role:integrity');
});

// ================== ATTENDANCE ==================
Route::middleware('auth')->group(function () {
    // Attendance List
    Route::get('/attendance', function () {
        return view('attendance.list');
    })->name('attendance.list');

    // Create Attendance
    Route::get('/attendance/create', function () {
        return view('attendance.create');
    })->name('attendance.create');

    // Store Attendance
    Route::post('/attendance', function () {
        // Logic to store attendance goes here later
    })->name('attendance.store');

    // Edit Attendance
    Route::get('/attendance/{id}/edit', function ($id) {
        return view('attendance.edit', compact('id'));
    })->name('attendance.edit');

    // Update Attendance
    Route::put('/attendance/{id}', function ($id) {
        // Logic to update attendance goes here later
    })->name('attendance.update');
});

// ================== HOD APPROVAL ==================
Route::middleware('role:hod')->group(function () {
    // View Pending Approvals
    Route::get('/hod/approvals', function () {
        return view('hod.approvals');
    })->name('hod.approvals');

    // Approve Attendance
    Route::post('/hod/approvals/{id}/approve', function ($id) {
        // Logic to approve attendance goes here later
    })->name('hod.approve');

    // Reject Attendance
    Route::post('/hod/approvals/{id}/reject', function ($id) {
        // Logic to reject attendance goes here later
    })->name('hod.reject');
});

// ================== USER MANAGEMENT ==================
Route::middleware('role:admin')->group(function () {
    // User List Page
    Route::get('/admin/users', function () {
        return view('admin.users.index');
    })->name('admin.users.index');

    // Create User
    Route::get('/admin/users/create', function () {
        return view('admin.users.create');
    })->name('admin.users.create');

    // Store New User
    Route::post('/admin/users', function () {
        // Logic to store a new user goes here later
    })->name('admin.users.store');

    // Edit User
    Route::get('/admin/users/{id}/edit', function ($id) {
        return view('admin.users.edit', compact('id'));
    })->name('admin.users.edit');

    // Update User
    Route::put('/admin/users/{id}', function ($id) {
        // Logic to update user details goes here later
    })->name('admin.users.update');

    // Delete User
    Route::delete('/admin/users/{id}', function ($id) {
        // Logic to delete user goes here later
    })->name('admin.users.destroy');
});

// ================== REPORTS ==================
Route::middleware('role:admin|integrity')->group(function () {
    // View Report Generation Page
    Route::get('/admin/reports', function () {
        return view('reports.index');
    })->name('reports.index');

    // Generate Report
    Route::get('/admin/reports/generate', function () {
        // Logic for generating reports goes here later
    })->name('reports.generate');

    // Print Report
    Route::get('/admin/reports/print', function () {
        // Logic to handle print functionality goes here later
    })->name('reports.print');

    // Export Report to PDF
    Route::get('/admin/reports/export', function () {
        // Logic to handle export to PDF goes here later
    })->name('reports.export');
});