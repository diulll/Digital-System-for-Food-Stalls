<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CashierController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Cashier Routes (for Kasir role only)
    Route::prefix('cashier')->name('cashier.')->group(function () {
        Route::get('/dashboard', [CashierController::class, 'dashboard'])->name('dashboard');
        Route::post('/checkout', [CashierController::class, 'checkout'])->name('checkout');
        Route::get('/receipt/{id}', [CashierController::class, 'receipt'])->name('receipt');
    });

    // Admin-only Routes (protect these later with middleware if needed)
    Route::middleware('can:admin-access')->group(function () {
        // Category Management
        Route::resource('categories', CategoryController::class);

        // Menu Management
        Route::resource('menus', MenuController::class);

        // Order Management
        Route::resource('orders', OrderController::class);
        Route::post('orders/{order}/update-status', [OrderController::class, 'updateStatus'])
            ->name('orders.update-status');

        // Transaction History
        Route::resource('transactions', TransactionController::class)
            ->only(['index', 'show']);

        // Sales Reports
        Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    });
});

require __DIR__.'/auth.php';
