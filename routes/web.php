<?php

use Illuminate\Support\Facades\Route; // <-- Ensure this is at the top!
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;

Route::redirect('/', '/login');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

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
    Route::get('/attendance', [AttendanceController::class, 'index'])->middleware('role:Staff|HOD|Admin');
    Route::get('/attendance/create', [AttendanceController::class, 'create'])->middleware('role:Staff|HOD|Admin');
    Route::post('/attendance', [AttendanceController::class, 'store'])->middleware('role:Staff|HOD|Admin');
    Route::get('/attendance/{id}/edit', [AttendanceController::class, 'edit'])->middleware('role:Staff|HOD|Admin');
    Route::put('/attendance/{id}', [AttendanceController::class, 'update'])->middleware('role:Staff|HOD|Admin');
});

// Approval Routes (For HOD Only)
Route::middleware('auth')->group(function () {
    Route::post('/hod/approvals/{id}/approve', [ApprovalController::class, 'approve'])->middleware('role:HOD');
    Route::post('/hod/approvals/{id}/reject', [ApprovalController::class, 'reject'])->middleware('role:HOD');
});

// User Management Routes (For Admin Only)
Route::middleware('auth')->group(function () {
    Route::resource('admin/users', UserController::class)
        ->names('admin.users')
        ->middleware('role:Admin');
});

// Report Routes (For Admin and Integrity Unit)
Route::middleware('auth')->group(function () {
    
    Route::get('/admin/reports', [ReportController::class, 'index'])
        ->middleware('role:Admin|Integrity')
        ->name('reports.index');

    Route::get('/admin/reports/generate', [ReportController::class, 'generate'])
        ->middleware('role:Admin|Integrity')
        ->name('reports.generate'); // <-- THIS FIXES YOUR ERROR

    Route::get('/admin/reports/{id}/show', [ReportController::class, 'show'])
        ->middleware('role:Admin|Integrity')
        ->name('reports.show');

    Route::get('/admin/reports/{id}/print', [ReportController::class, 'print'])
        ->middleware('role:Admin|Integrity')
        ->name('reports.print');
});