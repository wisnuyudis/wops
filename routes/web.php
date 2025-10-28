<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SorController;
use App\Http\Controllers\DailyActivityController;
use App\Http\Controllers\WeeklyProgressController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;

// Auth routes
Route::get('/', [AuthController::class, 'showLogin']);
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // User Management (Admin only)
    Route::middleware(['auth'])->prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('users.index');
        Route::get('/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/', [UserController::class, 'store'])->name('users.store');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });
    
    // Customer Management (Admin only)
    Route::middleware(['auth'])->prefix('customers')->group(function () {
        Route::get('/', [CustomerController::class, 'index'])->name('customers.index');
        Route::get('/create', [CustomerController::class, 'create'])->name('customers.create');
        Route::post('/', [CustomerController::class, 'store'])->name('customers.store');
        Route::get('/{customer}/edit', [CustomerController::class, 'edit'])->name('customers.edit');
        Route::put('/{customer}', [CustomerController::class, 'update'])->name('customers.update');
        Route::delete('/{customer}', [CustomerController::class, 'destroy'])->name('customers.destroy');
    });
    
    // Product Management (Admin only)
    Route::middleware(['auth'])->prefix('products')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('products.index');
        Route::get('/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('/', [ProductController::class, 'store'])->name('products.store');
        Route::get('/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    });
    
    // SOR Management
    Route::prefix('sors')->group(function () {
        Route::get('/', [SorController::class, 'index'])->name('sors.index');
        Route::get('/create', [SorController::class, 'create'])->name('sors.create');
        Route::post('/', [SorController::class, 'store'])->name('sors.store');
        Route::get('/{sor}', [SorController::class, 'show'])->name('sors.show');
        Route::get('/{sor}/edit', [SorController::class, 'edit'])->name('sors.edit');
        Route::put('/{sor}', [SorController::class, 'update'])->name('sors.update');
        Route::delete('/{sor}', [SorController::class, 'destroy'])->name('sors.destroy');
    });
    
    // Daily Activity
    Route::prefix('daily-activities')->group(function () {
        Route::get('/', [DailyActivityController::class, 'index'])->name('daily-activities.index');
        Route::get('/create', [DailyActivityController::class, 'create'])->name('daily-activities.create');
        Route::post('/', [DailyActivityController::class, 'store'])->name('daily-activities.store');
        Route::get('/{dailyActivity}', [DailyActivityController::class, 'show'])->name('daily-activities.show');
        Route::get('/{dailyActivity}/edit', [DailyActivityController::class, 'edit'])->name('daily-activities.edit');
        Route::put('/{dailyActivity}', [DailyActivityController::class, 'update'])->name('daily-activities.update');
        Route::delete('/{dailyActivity}', [DailyActivityController::class, 'destroy'])->name('daily-activities.destroy');
        
        // Export (Admin only)
        Route::post('/export', [DailyActivityController::class, 'export'])->name('daily-activities.export')->middleware('admin');
    });
    
    // API Routes for AJAX
    Route::prefix('api')->group(function () {
        Route::get('/users/{user}/sors', [DailyActivityController::class, 'getUserSors'])->middleware('admin');
    });
    
    // Weekly Progress
    Route::prefix('weekly-progress')->group(function () {
        Route::get('/', [WeeklyProgressController::class, 'index'])->name('weekly-progress.index');
        Route::get('/create', [WeeklyProgressController::class, 'create'])->name('weekly-progress.create');
        Route::post('/', [WeeklyProgressController::class, 'store'])->name('weekly-progress.store');
        Route::get('/{weeklyProgress}', [WeeklyProgressController::class, 'show'])->name('weekly-progress.show');
        Route::get('/{weeklyProgress}/edit', [WeeklyProgressController::class, 'edit'])->name('weekly-progress.edit');
        Route::put('/{weeklyProgress}', [WeeklyProgressController::class, 'update'])->name('weekly-progress.update');
        Route::delete('/{weeklyProgress}', [WeeklyProgressController::class, 'destroy'])->name('weekly-progress.destroy');
        
        // Export (Admin only)
        Route::post('/export', [WeeklyProgressController::class, 'export'])->name('weekly-progress.export')->middleware('admin');
    });
});
