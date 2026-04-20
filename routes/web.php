<?php

use Illuminate\Support\Facades\Route; // <-- Ensure this is at the top!
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\IntegrityController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PasswordResetController;

// ---> ADD THIS NEW LINE <---
Route::redirect('/', '/login');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// User Profile Routes (Accessible by ALL logged-in users)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// Protecting the Dashboard Routes
Route::middleware('auth')->group(function () {
    // Admin Dashboard
    Route::get('/admin/dashboard', [DashboardController::class, 'adminDashboard'])
        ->middleware('role:Admin')
        ->name('admin.dashboard'); // <-- Added name

    // HOD Dashboard
    Route::get('/hod/dashboard', [DashboardController::class, 'hodDashboard'])
        ->middleware('role:HOD')
        ->name('hod.dashboard'); // <-- Added name

    // Integrity Dashboard
    Route::get('/integrity/dashboard', [DashboardController::class, 'integrityDashboard'])
        ->middleware('role:Integrity')
        ->name('integrity.dashboard'); // <-- Added name

    // User Dashboard (default)
    Route::get('/user/dashboard', [DashboardController::class, 'userDashboard'])
        ->middleware('role:Staff') // <-- Changed to 'Staff' to match your seeder
        ->name('dashboard'); // <-- Added name (matches the fallback in AuthController)
});

// Attendance Routes
Route::middleware('auth')->group(function () {
    // Updated roles to match database capitalization
    Route::get('/attendance', [AttendanceController::class, 'index'])
        ->middleware('role:Staff|HOD|Admin')
        ->name('attendance.list');

    Route::get('/attendance/create', [AttendanceController::class, 'create'])
        ->middleware('role:Staff|HOD|Admin')
        ->name('attendance.create');

    Route::post('/attendance', [AttendanceController::class, 'store'])
        ->middleware('role:Staff|HOD|Admin')
        ->name('attendance.store');

    Route::get('/attendance/{id}/edit', [AttendanceController::class, 'edit'])
        ->middleware('role:Staff|HOD|Admin')
        ->name('attendance.edit'); // <-- THIS FIXES YOUR ERROR!

    Route::put('/attendance/{id}', [AttendanceController::class, 'update'])
        ->middleware('role:Staff|HOD|Admin')
        ->name('attendance.update');
});

// HOD Approval Routes (Approves Staff)
    Route::middleware(['auth', 'role:HOD'])->group(function () {
    Route::get('/hod/approvals', [ApprovalController::class, 'index'])->name('hod.approvals');
    Route::post('/hod/approvals/{id}/approve', [ApprovalController::class, 'approve'])->name('hod.approve');
    Route::post('/hod/approvals/{id}/reject', [ApprovalController::class, 'reject'])->name('hod.reject');
});

// Integrity Approval Routes (Approves HODs)
    Route::middleware(['auth', 'role:Integrity'])->group(function () {
    Route::get('/integrity/approvals', [IntegrityController::class, 'approvals'])->name('integrity.approvals');
    Route::post('/integrity/approvals/{id}/approve', [IntegrityController::class, 'approve'])->name('integrity.approve');
    Route::post('/integrity/approvals/{id}/reject', [IntegrityController::class, 'reject'])->name('integrity.reject');
});

// User Management Routes (For Admin Only)
Route::middleware('auth')->group(function () {
    Route::resource('admin/users', UserController::class)
        ->names('admin.users')
        ->middleware('role:Admin');
});

// Report Routes (For Admin and Integrity Unit)
Route::middleware('auth')->group(function () {
    Route::get('/admin/reports', [ReportController::class, 'index'])->middleware('role:Admin|Integrity')->name('reports.index');
    Route::get('/admin/reports/generate', [ReportController::class, 'generate'])->middleware('role:Admin|Integrity')->name('reports.generate');
    
    // REMOVED {id} FROM THESE ROUTES:
    Route::get('/admin/reports/print', [ReportController::class, 'print'])->middleware('role:Admin|Integrity')->name('reports.print');
    Route::get('/admin/reports/export', [ReportController::class, 'export'])->middleware('role:Admin|Integrity')->name('reports.export');
});

// Password Reset Routes
Route::middleware('guest')->group(function () {
    Route::get('/forgot-password', [PasswordResetController::class, 'request'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'email'])->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'reset'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'update'])->name('password.update');
});