<?php

use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductsController;
use App\Middleware\TenantMiddleware;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::domain('localhost')->get('/', function(){
    return Inertia::render('Welcome');
});

require __DIR__.'/auth_public.php';

Route::middleware(TenantMiddleware::class)->group(function () {
    Route::get('/', [HomeController::class, 'home'])->name('home');
    Route::get('/product/{slug}', [ProductsController::class, 'show'])->name('product.show');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    require __DIR__.'/auth.php';
});

Route::middleware(['auth', 'verified', TenantMiddleware::class])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('orders', [OrderController::class, 'index']);

    Route::resource('categories', CategoriesController::class);

    Route::get('products', [ProductsController::class, "index"])->name('products.index');
    Route::post('products', [ProductsController::class, "store"]);
    Route::post('products/{id}', [ProductsController::class, 'update']);
    Route::delete('products/{id}', [ProductsController::class, 'destroy']);

});

require __DIR__.'/settings.php';
