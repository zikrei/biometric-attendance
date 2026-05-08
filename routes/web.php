<?php

use Illuminate\Support\Facades\Route; 
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\IntegrityController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PasswordResetController;

/**
 * PHASE 1: PUBLIC ACCESS & AUTHENTICATION GATEWAYS
 * OBJECTIVE: Manage system entry, session termination, and initial redirection logic.
 * PROCEDURES: 
 * - Redirects root URL traffic to the login interface to prevent unhandled view exceptions.
 * - Maps AuthController methods for secure session management.
 */
Route::redirect('/', '/login');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


/**
 * PHASE 2: AUTHENTICATED SELF-SERVICE
 * OBJECTIVE: Provide secure, authenticated access for personal data modification.
 * MIDDLEWARE: Enforces the 'auth' guard to protect sensitive profile modification.
 */
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

/**
 * PHASE 3: ROLE-SPECIFIC DASHBOARD ORCHESTRATION
 * OBJECTIVE: Route users to permission-tiered command centers based on their designation.
 * PROCEDURES: 
 * - Uses individual middleware checks to route Admin, HOD, Integrity, and Staff (default) users.
 * - Configured 'dashboard' as the primary fallback name for navigation logic.
 */
Route::middleware('auth')->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'adminDashboard'])
        ->middleware('role:Admin')
        ->name('admin.dashboard');

    Route::get('/hod/dashboard', [DashboardController::class, 'hodDashboard'])
        ->middleware('role:HOD')
        ->name('hod.dashboard');

    Route::get('/integrity/dashboard', [DashboardController::class, 'integrityDashboard'])
        ->middleware('role:Integrity')
        ->name('integrity.dashboard');

    Route::get('/user/dashboard', [DashboardController::class, 'userDashboard'])
        ->middleware('role:Staff') 
        ->name('dashboard'); 
});

/**
 * PHASE 4: ATTENDANCE LIFECYCLE MANAGEMENT
 * OBJECTIVE: Facilitate shift recording, log modification, and document generation.
 * PROCEDURES: 
 * - Standardizes access for Staff, HOD, and Admin roles via pipe-delimited middleware.
 * - Fixes route identification for 'attendance.edit' to ensure record persistence.
 */
Route::middleware('auth')->group(function () {
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
        ->name('attendance.edit'); 

    Route::put('/attendance/{id}', [AttendanceController::class, 'update'])
        ->middleware('role:Staff|HOD|Admin')
        ->name('attendance.update');
    
    Route::get('/attendance/print', [AttendanceController::class, 'print'])
        ->middleware('role:Staff|HOD|Admin')
        ->name('attendance.print');
});

/**
 * PHASE 5: HOD DISCREPANCY APPROVAL WORKFLOW
 * OBJECTIVE: Permit Departmental Heads to review and authorize staff attendance justifications.
 * PROCEDURES: Restricts modification actions (Approve/Reject) strictly to the 'HOD' role context.
 */
    Route::middleware(['auth', 'role:HOD'])->group(function () {
    Route::get('/hod/approvals', [ApprovalController::class, 'index'])->name('hod.approvals');
    Route::post('/hod/approvals/{id}/approve', [ApprovalController::class, 'approve'])->name('hod.approve');
    Route::post('/hod/approvals/{id}/reject', [ApprovalController::class, 'reject'])->name('hod.reject');
});

/**
 * PHASE 6: INTEGRITY UNIT OVERSIGHT WORKFLOW
 * OBJECTIVE: Provide high-level verification of departmental management (HODs).
 * PROCEDURES: Segregates HOD-level approval logic into an independent 'Integrity' authorization tier.
 */
    Route::middleware(['auth', 'role:Integrity'])->group(function () {
    Route::get('/integrity/approvals', [IntegrityController::class, 'approvals'])->name('integrity.approvals');
    Route::post('/integrity/approvals/{id}/approve', [IntegrityController::class, 'approve'])->name('integrity.approve');
    Route::post('/integrity/approvals/{id}/reject', [IntegrityController::class, 'reject'])->name('integrity.reject');
});

/**
 * PHASE 7: GLOBAL USER & RESOURCE ADMINISTRATION
 * OBJECTIVE: Centralize employee registry management and administrative reporting.
 * PROCEDURES: 
 * - Maps the UserController resource methods with the 'admin.' naming prefix.
 * - Explicitly defines the 'print' route prior to the resource to avoid ID collision.
 */
Route::middleware(['auth', 'role:Admin'])->group(function () {
    Route::get('/admin/users/print', [UserController::class, 'print'])->name('admin.users.print');
    Route::resource('admin/users', UserController::class)->names('admin.users');
});

/**
 * PHASE 8: MULTI-TIER REPORTING & ANALYTICS
 * OBJECTIVE: Provide filtered insights into system-wide attendance patterns.
 * PROCEDURES: Permits shared access for Admin, Integrity, and HOD roles to generate, print, and export reports.
 */
Route::middleware('auth')->group(function () {
    Route::get('/admin/reports', [ReportController::class, 'index'])
        ->middleware('role:Admin|Integrity|HOD')
        ->name('reports.index');

    Route::get('/admin/reports/generate', [ReportController::class, 'generate'])
        ->middleware('role:Admin|Integrity|HOD')
        ->name('reports.generate');

    Route::get('/admin/reports/print', [ReportController::class, 'print'])
        ->middleware('role:Admin|Integrity|HOD')
        ->name('reports.print');

    Route::get('/admin/reports/export', [ReportController::class, 'export'])
        ->middleware('role:Admin|Integrity|HOD')
        ->name('reports.export');
});

/**
 * PHASE 9: TOKEN-BASED PASSWORD RECOVERY
 * OBJECTIVE: Facilitate account recovery for unauthenticated users (Guests).
 * PROCEDURES: Enforces 'guest' middleware to ensure reset requests are only initiated from a logged-out state.
 */
Route::middleware('guest')->group(function () {
    Route::get('/forgot-password', [PasswordResetController::class, 'request'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'email'])->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'reset'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'update'])->name('password.update');
});