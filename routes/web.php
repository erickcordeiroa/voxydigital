<?php

use App\Http\Controllers\BannerController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\ProductVariationController;
use App\Http\Controllers\WebhookController;
use App\Middleware\TenantMiddleware;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::domain('localhost')->get('/', function () {
    return Inertia::render('Welcome');
})->name('welcome');

// Webhook do Mercado Pago (sem middleware de autenticação)
Route::post('/webhook/mercadopago', [WebhookController::class, 'mercadoPago'])->name('webhook.mercadopago');

require __DIR__ . '/auth_public.php';

Route::middleware(TenantMiddleware::class)->group(function () {
    Route::get('/', [HomeController::class, 'home'])->name('home');
    Route::get('/product/{slug}', [ProductsController::class, 'show'])->name('product.show');
    Route::get('/category/{slug}', [CategoriesController::class, 'showPublic'])->name('category.public.show');
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/{order}/check-payment', [OrderController::class, 'checkPayment'])->name('orders.check-payment');
    require __DIR__ . '/auth.php';
});

Route::middleware(['auth', 'verified', TenantMiddleware::class])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('orders', [OrderController::class, 'index']);
    Route::post('orders/update-status', [OrderController::class, 'update'])->name('orders.update');

    Route::resource('categories', CategoriesController::class);
    Route::resource('banners', BannerController::class);

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

require __DIR__ . '/settings.php';
