<?php

use App\Http\Controllers\LeadController;
use App\Http\Controllers\SubscriptionController;
use Illuminate\Support\Facades\Route;

Route::resource('/leads', LeadController::class);

// Subscription routes
Route::prefix('subscriptions')->group(function () {
    Route::post('/', [SubscriptionController::class, 'store'])->name('subscriptions.store');
    Route::get('/current', [SubscriptionController::class, 'current'])->name('subscriptions.current');
    Route::post('/{id}/cancel', [SubscriptionController::class, 'cancel'])->name('subscriptions.cancel');
});