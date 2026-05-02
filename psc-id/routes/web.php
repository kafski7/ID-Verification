<?php

use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AuditController;
use App\Http\Controllers\Admin\CardController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\QrController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Staff\AuthController as StaffAuthController;
use App\Http\Controllers\Staff\ForgotPasswordController as StaffForgotPasswordController;
use App\Http\Controllers\Staff\PrivacyController as StaffPrivacyController;
use App\Http\Controllers\Staff\ProfileController as StaffProfileController;
use App\Http\Controllers\Staff\ResetPasswordController as StaffResetPasswordController;
use App\Http\Controllers\VerifyController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::get('/', fn () => redirect()->route('login'));

// Public QR verification — no auth required
Route::get('/verify/{token}', [VerifyController::class, 'show'])->name('verify.show');

Route::middleware(['auth', 'role:VIEWER,HR_ADMIN,SUPER_ADMIN'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    // Staff (read available to all authenticated roles; write restricted in controller/form request)
    Route::resource('staff', StaffController::class)->except(['destroy']);
    Route::patch('staff/{staff}/deactivate', [StaffController::class, 'deactivate'])->name('staff.deactivate');

    // QR token management (nested under staff)
    Route::prefix('staff/{staff}/qr')->name('staff.qr.')->group(function () {
        Route::get('/',             [QrController::class, 'show'])->name('show');
        Route::patch('/regenerate', [QrController::class, 'regenerate'])->name('regenerate');
        Route::delete('/revoke',    [QrController::class, 'revoke'])->name('revoke');
    });

    // ID card preview + PDF download
    Route::get('staff/{staff}/card',     [CardController::class, 'show'])->name('staff.card.show');
    Route::get('staff/{staff}/card/pdf', [CardController::class, 'pdf'])->name('staff.card.pdf');

    // Audit / scan log (HR_ADMIN and SUPER_ADMIN only — enforced in middleware below)
    Route::get('scan-logs', [AuditController::class, 'index'])
        ->middleware('role:HR_ADMIN,SUPER_ADMIN')
        ->name('scan-logs.index');

    // Admin user management (SUPER_ADMIN only)
    Route::middleware('role:SUPER_ADMIN')->group(function () {
        Route::resource('admin-users', AdminUserController::class)->except(['destroy', 'show']);
        Route::patch('admin-users/{adminUser}/deactivate', [AdminUserController::class, 'deactivate'])
            ->name('admin-users.deactivate');
    });

    // Serve staff photos securely (never exposes the private path)
    Route::get('staff/{staff}/photo', function (\App\Models\Staff $staff) {
        abort_unless($staff->photo_path && Storage::disk('private')->exists($staff->photo_path), 404);
        return response()->file(Storage::disk('private')->path($staff->photo_path));
    })->name('staff.photo');
});

require __DIR__.'/auth.php';

// ──────────────────────────────────────────────────────
// Staff self-service portal
// ──────────────────────────────────────────────────────
Route::prefix('staff')->name('staff.')->group(function () {

    // Guest-only routes (login, password reset)
    Route::get('/login',  [StaffAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [StaffAuthController::class, 'login'])->name('login.post');

    Route::get('/forgot-password',  [StaffForgotPasswordController::class, 'show'])->name('password.request');
    Route::post('/forgot-password', [StaffForgotPasswordController::class, 'store'])->name('password.email');

    Route::get('/reset-password/{token}', [StaffResetPasswordController::class, 'show'])->name('password.reset');
    Route::post('/reset-password',        [StaffResetPasswordController::class, 'store'])->name('password.update');

    // Logout (available even with stale session)
    Route::post('/logout', [StaffAuthController::class, 'logout'])->name('logout');

    // Authenticated routes
    Route::middleware('auth:staff')->group(function () {
        Route::get('/portal',         [StaffProfileController::class, 'show'])->name('portal');

        Route::get('/profile/edit',   [StaffProfileController::class, 'editDetails'])->name('profile.edit');
        Route::patch('/profile',      [StaffProfileController::class, 'updateDetails'])->name('profile.update');

        Route::get('/password/edit',  [StaffProfileController::class, 'editPassword'])->name('password.edit');
        Route::patch('/password',     [StaffProfileController::class, 'updatePassword'])->name('password.change');

        Route::get('/privacy',        [StaffPrivacyController::class, 'edit'])->name('privacy.edit');
        Route::patch('/privacy',      [StaffPrivacyController::class, 'update'])->name('privacy.update');
    });
});
