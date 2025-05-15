<?php

use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductsController;
use App\Middleware\TenantMiddleware;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::domain('localhost')->group(function () {
    Route::get('/', function(){
        return Inertia::render('Welcome'); // Página da sua empresa
    });
});

Route::domain('{tenant}.localhost')->middleware(TenantMiddleware::class)->group(function () {
    Route::get('/', [HomeController::class, 'home'])->name('home');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
});

Route::middleware(['auth', 'verified', TenantMiddleware::class])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('orders', [OrderController::class, 'index']);

    Route::resource('categories', CategoriesController::class);

    Route::get('products', [ProductsController::class, "index"]);
    Route::post('products', [ProductsController::class, "store"]);
    Route::post('products/{id}', [ProductsController::class, 'update']);
    Route::delete('products/{id}', [ProductsController::class, 'destroy']);

});



require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
