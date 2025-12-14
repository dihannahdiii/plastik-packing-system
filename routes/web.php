<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\LoginController;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Admin Routes (Order Management) - Protected
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::get('/products/search', [OrderController::class, 'searchProducts'])->name('products.search');
});

// Warehouse Routes (Gudang) - Protected
Route::prefix('gudang')->name('warehouse.')->middleware(['auth', 'role:gudang'])->group(function () {
    Route::get('/', [WarehouseController::class, 'index'])->name('index');
    Route::get('/orders/{order}', [WarehouseController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order}/confirm', [WarehouseController::class, 'confirm'])->name('orders.confirm');
    Route::get('/stock', [WarehouseController::class, 'stock'])->name('stock');
    Route::post('/stock/update', [WarehouseController::class, 'updateStock'])->name('stock.update');
    Route::get('/products/search', [WarehouseController::class, 'searchProducts'])->name('products.search');
    Route::get('/pending-count', [WarehouseController::class, 'getPendingCount'])->name('pending.count');
});
