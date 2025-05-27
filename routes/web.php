<?php

use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\ProductVariationController;
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
    Route::post('orders/update-status', [OrderController::class, 'update'])->name('orders.update');

    Route::resource('categories', CategoriesController::class);

    Route::get('products', [ProductsController::class, "index"])->name('products.index');
    Route::get('products/create', [ProductsController::class, "create"])->name('products.create');
    Route::post('products', [ProductsController::class, "store"]);
    Route::get('products/edit/{id}', [ProductsController::class, "edit"])->name('products.edit');
    Route::post('products/{id}', [ProductsController::class, 'update']);
    Route::delete('products/{id}', [ProductsController::class, 'destroy']);

    Route::post('/products/{id}/variations', [ProductVariationController::class, 'store'])->name('variations.store');
    Route::put('/products/{id}/variations', [ProductVariationController::class, 'update'])->name('variations.update');
    Route::delete('/products/{id}/variations', [ProductVariationController::class, 'destroy'])->name('variations.delete');

});

require __DIR__.'/settings.php';
