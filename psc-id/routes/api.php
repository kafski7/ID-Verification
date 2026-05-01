<?php

use App\Http\Controllers\Api\V1\VerifyController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::get('/verify', [VerifyController::class, 'show'])
        ->middleware('throttle:api-verify')
        ->name('api.v1.verify');
});
