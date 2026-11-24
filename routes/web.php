<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
})->name('home');

Route::get('/about', [PageController::class, 'about'])
    ->name('about');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');

Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Customers
    |--------------------------------------------------------------------------
    */

    Route::patch('/customers/{customer}/restore', [
        CustomerController::class,
        'restore',
    ])->name('customers.restore');

    Route::delete('/customers/{customer}/permanent', [
        CustomerController::class,
        'forceDestroy',
    ])->name('customers.forceDestroy');

    Route::resource('customers', CustomerController::class)
        ->except(['show']);

    /*
    |--------------------------------------------------------------------------
    | Staff / Users
    |--------------------------------------------------------------------------
    */

    Route::resource('users', UserController::class)
        ->except(['show']);

    /*
    |--------------------------------------------------------------------------
    | Products
    |--------------------------------------------------------------------------
    */

    Route::resource('products', ProductController::class)
        ->except(['show']);

    /*
    |--------------------------------------------------------------------------
    | Sales
    |--------------------------------------------------------------------------
    */

    Route::get('/sales/export', [
        SaleController::class,
        'export',
    ])->name('sales.export');

    Route::get('/sales/{sale}/receipt', [
        SaleController::class,
        'receipt',
    ])->name('sales.receipt');

    Route::resource('sales', SaleController::class)
        ->only(['index', 'create', 'store', 'show']);

    /*
    |--------------------------------------------------------------------------
    | Profile
    |--------------------------------------------------------------------------
    */

    Route::get('/profile', [
        ProfileController::class,
        'edit',
    ])->name('profile.edit');

    Route::patch('/profile', [
        ProfileController::class,
        'update',
    ])->name('profile.update');

    Route::get('/profile/password', [
        ProfileController::class,
        'editPassword',
    ])->name('profile.password.edit');

    Route::patch('/profile/password', [
        ProfileController::class,
        'updatePassword',
    ])->name('profile.password.update');

    Route::delete('/profile', [
        ProfileController::class,
        'destroy',
    ])->name('profile.destroy');
});

require __DIR__.'/auth.php';