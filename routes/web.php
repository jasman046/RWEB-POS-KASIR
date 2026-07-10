<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Redirect Root
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('dashboard');
});

/*
|--------------------------------------------------------------------------
| Dashboard POS
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [OrderController::class, 'index'])
        ->name('dashboard');

    Route::get('/products', [ProductController::class, 'index'])
        ->name('products.index');

    Route::get('/products/category/{category}', [ProductController::class, 'byCategory'])
        ->name('products.category');

    Route::get('/api/products/category/{category}', [ProductController::class, 'getByCategory']);

    Route::post('/api/add-to-cart', [OrderController::class, 'addToCart']);

    Route::post('/api/update-cart', [OrderController::class, 'updateCart']);

    Route::get('/checkout', [OrderController::class, 'checkout'])
        ->name('checkout');

    Route::post('/api/process-payment', [OrderController::class, 'processPayment']);

    Route::get('/receipt/{orderId}', [OrderController::class, 'receipt'])
        ->name('receipt');

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Admin Only
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])->group(function () {

    Route::get('/transactions', [TransactionController::class, 'index'])
        ->name('transactions.index');

    Route::get('/transactions/{transaction}/download', [TransactionController::class, 'downloadReceipt'])
        ->name('transactions.download');

});

/*
|--------------------------------------------------------------------------
| Auth
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';