<?php

use App\Http\Controllers\Admin\CardController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\QrController;
use App\Http\Controllers\Admin\StaffController;
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

    // Serve staff photos securely (never exposes the private path)
    Route::get('staff/{staff}/photo', function (\App\Models\Staff $staff) {
        abort_unless($staff->photo_path && Storage::disk('private')->exists($staff->photo_path), 404);
        return response()->file(Storage::disk('private')->path($staff->photo_path));
    })->name('staff.photo');
});

require __DIR__.'/auth.php';
