<?php

use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\Settings\PasswordController;
use App\Http\Controllers\Settings\ProfileController;
use App\Http\Controllers\TenantController;
use App\Middleware\TenantMiddleware;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware(['auth', TenantMiddleware::class])->group(function () {
    Route::redirect('settings', '/settings/profile');

    Route::get('settings/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('settings/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('settings/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('settings/password', [PasswordController::class, 'edit'])->name('password.edit');
    Route::put('settings/password', [PasswordController::class, 'update'])->name('password.update');

    Route::get('settings/appearance', [TenantController::class, 'show'])->name('appearance');
    Route::post('settings/appearance', [TenantController::class, 'update']);

    Route::get('settings/payment-methods', [PaymentMethodController::class, 'index'])->name('payment-methods.index');
    Route::post('settings/payment-methods', [PaymentMethodController::class, 'store'])->name('payment-methods.store');
    Route::post('settings/payment-methods/{id}/toggle', [PaymentMethodController::class, 'toggle'])->name('payment-methods.toggle');
    Route::put('settings/payment-methods/{id}', [PaymentMethodController::class, 'update'])->name('payment-methods.update');

    //TODO: Implementar assinatura futuramente.
    // Route::get('settings/subscription', [\App\Http\Controllers\Settings\SubscriptionController::class, 'edit'])->name('subscription.edit');
    // Route::post('settings/subscription', [\App\Http\Controllers\Settings\SubscriptionController::class, 'store'])->name('subscription.store');
    // Route::post('settings/subscription/cancel', [\App\Http\Controllers\Settings\SubscriptionController::class, 'cancel'])->name('subscription.cancel');
    // Route::post('settings/subscription/{id}/reactivate', [\App\Http\Controllers\Settings\SubscriptionController::class, 'reactivate'])->name('subscription.reactivate');
});
