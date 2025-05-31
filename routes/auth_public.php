<?php

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\TenantController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::post('register', [TenantController::class, 'store'])
        ->name('tenant.register');
});
