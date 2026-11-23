<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// PUBLIC ROUTES
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');

// DASHBOARD
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');

// AUTHENTICATED ROUTES
Route::middleware('auth')->group(function () {

    // CUSTOMERS MODULE
    // Includes Archive Customer
    Route::resource('customers', CustomerController::class)
        ->except(['show']);

    // USERS MODULE
    Route::resource('users', UserController::class)
        ->except(['show', 'destroy']);

    // PRODUCTS MODULE
    // Includes Archive Product
    Route::resource('products', ProductController::class)
        ->except(['show']);

    // SALES MODULE

    // Export Sales History to CSV
    Route::get('/sales/export', [SaleController::class, 'export'])
        ->name('sales.export');

    // Download PDF Receipt
    Route::get('/sales/{sale}/receipt', [SaleController::class, 'receipt'])
        ->name('sales.receipt');

    // Record Sale, Sales History, and Transaction Details
    Route::resource('sales', SaleController::class)
        ->only(['index', 'create', 'store', 'show']);

    // PROFILE ROUTES
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])
        ->name('profile.password.update');
});

// AUTHENTICATION ROUTES
require __DIR__.'/auth.php';

