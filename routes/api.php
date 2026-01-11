<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\MenuController;
use App\Http\Controllers\Api\V1\OrderController;
use App\Http\Controllers\Api\V1\TransactionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// ==================== PUBLIC ROUTES (No Authentication Required) ====================
Route::prefix('v1')->name('api.v1.')->group(function () {
    
    // Authentication Routes
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    
    // Public Menu & Categories (for customer view)
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('categories.show');
    Route::get('/menus', [MenuController::class, 'index'])->name('menus.index');
    Route::get('/menus/{menu}', [MenuController::class, 'show'])->name('menus.show');
});

// ==================== PROTECTED ROUTES (Authentication Required) ====================
Route::prefix('v1')->name('api.v1.')->middleware('auth:sanctum')->group(function () {
    
    // Auth - User Profile & Logout
    Route::get('/user', [AuthController::class, 'user'])->name('user');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::put('/user/update', [AuthController::class, 'updateProfile'])->name('user.update');
    Route::put('/user/password', [AuthController::class, 'updatePassword'])->name('user.password');
    
    // ==================== ADMIN ONLY ROUTES ====================
    Route::middleware('role:Admin')->group(function () {
        // Category Management (Full CRUD)
        Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
        
        // Menu Management (Full CRUD)
        Route::post('/menus', [MenuController::class, 'store'])->name('menus.store');
        Route::put('/menus/{menu}', [MenuController::class, 'update'])->name('menus.update');
        Route::delete('/menus/{menu}', [MenuController::class, 'destroy'])->name('menus.destroy');
        
        // All Orders (Admin can see all orders)
        Route::get('/admin/orders', [OrderController::class, 'index'])->name('admin.orders.index');
        Route::get('/admin/orders/{order}', [OrderController::class, 'show'])->name('admin.orders.show');
        
        // All Transactions (Admin can see all transactions)
        Route::get('/admin/transactions', [TransactionController::class, 'index'])->name('admin.transactions.index');
        Route::get('/admin/transactions/{transaction}', [TransactionController::class, 'show'])->name('admin.transactions.show');
        
        // Reports
        Route::get('/admin/reports/daily', [TransactionController::class, 'dailyReport'])->name('admin.reports.daily');
        Route::get('/admin/reports/monthly', [TransactionController::class, 'monthlyReport'])->name('admin.reports.monthly');
    });
    
    // ==================== CASHIER & ADMIN ROUTES ====================
    Route::middleware('role:Cashier,Admin')->group(function () {
        // Order Management
        Route::get('/orders', [OrderController::class, 'myOrders'])->name('orders.index');
        Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
        Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
        Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
        Route::post('/orders/{order}/complete', [OrderController::class, 'complete'])->name('orders.complete');
        
        // Transaction (Cashier's own transactions)
        Route::get('/transactions', [TransactionController::class, 'myTransactions'])->name('transactions.index');
        Route::get('/transactions/{transaction}', [TransactionController::class, 'show'])->name('transactions.show');
    });
});
