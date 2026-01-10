<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\CashierController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

// Redirect to appropriate dashboard based on role
Route::get('/dashboard', function () {
    $user = auth()->user();
    if ($user->role && $user->role->name === 'Cashier') {
        return redirect()->route('cashier.dashboard');
    }
    return app(DashboardController::class)->index();
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ==================== ADMIN ROUTES ====================
    Route::middleware('role:Admin')->group(function () {
        // Category Management (Admin Only)
        Route::resource('categories', CategoryController::class);

        // Menu Management (Admin Only)
        Route::resource('menus', MenuController::class);

        // All Orders Management (Admin)
        Route::resource('orders', OrderController::class);
        Route::post('orders/{order}/update-status', [OrderController::class, 'updateStatus'])
            ->name('orders.update-status');

        // Transaction History (Admin - All)
        Route::resource('transactions', TransactionController::class)
            ->only(['index', 'show']);

        // Sales Reports (Admin Only)
        Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    });

    // ==================== CASHIER ROUTES ====================
    Route::middleware('role:Cashier,Admin')->prefix('cashier')->name('cashier.')->group(function () {
        // Cashier Dashboard
        Route::get('/dashboard', [CashierController::class, 'dashboard'])->name('dashboard');

        // POS (Point of Sale)
        Route::get('/pos', [CashierController::class, 'pos'])->name('pos');
        Route::post('/pos/order', [CashierController::class, 'storeOrder'])->name('pos.store');
        Route::get('/pos/menus', [CashierController::class, 'getMenus'])->name('pos.menus');

        // Order Management for Cashier
        Route::get('/orders', [CashierController::class, 'pendingOrders'])->name('orders');
        Route::get('/orders/{order}/receipt', [CashierController::class, 'getOrderReceipt'])->name('orders.receipt');
        Route::patch('/orders/{order}/status', [CashierController::class, 'updateOrderStatus'])->name('orders.status');
        Route::post('/orders/{order}/complete', [CashierController::class, 'completeOrder'])->name('orders.complete');

        // Transaction History for Cashier
        Route::get('/transactions', [CashierController::class, 'transactions'])->name('transactions');
    });
});

require __DIR__.'/auth.php';
