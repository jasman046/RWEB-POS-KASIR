<?php
 
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;
 
Route::get('/', [OrderController::class, 'index'])->name('index');
 
// Products
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/category/{category}', [ProductController::class, 'byCategory'])->name('products.category');
Route::get('/api/products/category/{category}', [ProductController::class, 'getByCategory']);
 
// Cart & Order
Route::post('/api/add-to-cart', [OrderController::class, 'addToCart']);
Route::post('/api/update-cart', [OrderController::class, 'updateCart']);
Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout');
Route::post('/api/process-payment', [OrderController::class, 'processPayment']);
Route::get('/receipt/{orderId}', [OrderController::class, 'receipt'])->name('receipt');
 