<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ReportController;
use App\Models\Order;

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
| Dashboard POS (Wajib Login - Kasir & Admin Bisa Akses)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [OrderController::class, 'index'])->name('dashboard');

    // Products (Kasir: tampilan menu)
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/category/{category}', [ProductController::class, 'byCategory'])->name('products.category');
    Route::get('/api/products/category/{category}', [ProductController::class, 'getByCategory']);

    // Cart & Order
    Route::post('/api/add-to-cart', [OrderController::class, 'addToCart']);
    Route::post('/api/update-cart', [OrderController::class, 'updateCart']);
    Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout');
    Route::post('/api/process-payment', [OrderController::class, 'processPayment']);
    Route::get('/receipt/{orderId}', [OrderController::class, 'receipt'])->name('receipt');

    // Profile (Bawaan Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Setting
    Route::get('/setting', function () {
        return view('setting.index');
    })->name('setting');

    // Setting: Update Profile
    Route::post('/setting/profile', [ProfileController::class, 'updateSettings'])->name('setting.profile');

    // Setting: Ganti Password
    Route::post('/setting/password', [ProfileController::class, 'updatePassword'])->name('setting.password');

    // Setting: Upload Avatar
    Route::post('/setting/avatar', [ProfileController::class, 'updateAvatar'])->name('setting.avatar');

    // Notification
    Route::get('/notification', function () {
        $orders = Order::with('orderItems.product')->latest()->get();
        return view('notification.index', compact('orders'));
    })->name('notification');

});

/*
|--------------------------------------------------------------------------
| Admin Only (HANYA Admin yang Bisa Akses)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->group(function () {

    // Transaksi
    Route::resource('transactions', TransactionController::class);
    Route::get('/transactions/{transaction}/download', [TransactionController::class, 'downloadReceipt'])->name('transactions.download');

    // Admin: CRUD Produk
    Route::get('/admin/products', [ProductController::class, 'adminIndex'])->name('admin.products.index');
    Route::get('/admin/products/create', [ProductController::class, 'create'])->name('admin.products.create');
    Route::post('/admin/products', [ProductController::class, 'store'])->name('admin.products.store');
    Route::get('/admin/products/{product}/edit', [ProductController::class, 'edit'])->name('admin.products.edit');
    Route::put('/admin/products/{product}', [ProductController::class, 'update'])->name('admin.products.update');
    Route::delete('/admin/products/{product}', [ProductController::class, 'destroy'])->name('admin.products.destroy');

    // Admin: Manajemen Kasir
    Route::get('/admin/kasir', [AdminController::class, 'index'])->name('admin.kasir.index');
    Route::get('/admin/kasir/create', [AdminController::class, 'create'])->name('admin.kasir.create');
    Route::post('/admin/kasir', [AdminController::class, 'store'])->name('admin.kasir.store');
    Route::delete('/admin/kasir/{kasir}', [AdminController::class, 'destroy'])->name('admin.kasir.destroy');

    // Admin: Laporan Penjualan
    Route::get('/admin/reports', [ReportController::class, 'index'])->name('admin.reports');

});

/*
|--------------------------------------------------------------------------
| Auth (Login, Password Reset, dll)
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';