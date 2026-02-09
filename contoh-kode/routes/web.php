<?php

/**
 * CONTOH ROUTES - web.php
 * 
 * File ini berisi definisi semua routes untuk aplikasi web.
 * Lokasi: routes/web.php
 */

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\DashboardController;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES (Tanpa Login)
|--------------------------------------------------------------------------
*/

// Halaman utama
Route::get('/', [HomeController::class, 'index'])->name('home');

// Halaman about
Route::get('/about', [HomeController::class, 'about'])->name('about');

// Halaman contact
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::post('/contact', [HomeController::class, 'sendContact'])->name('contact.send');

/*
|--------------------------------------------------------------------------
| GUEST ROUTES (Hanya untuk yang belum login)
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    // Login
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    
    // Register
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    
    // Forgot Password
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])
        ->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])
        ->name('password.email');
});

/*
|--------------------------------------------------------------------------
| AUTH ROUTES (Membutuhkan Login)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    
    /*
    |--------------------------------------------------------------------------
    | RESOURCE ROUTES - Products
    |--------------------------------------------------------------------------
    | Route::resource() otomatis membuat 7 routes:
    | GET    /products           -> index
    | GET    /products/create    -> create
    | POST   /products           -> store
    | GET    /products/{product} -> show
    | GET    /products/{product}/edit -> edit
    | PUT    /products/{product} -> update
    | DELETE /products/{product} -> destroy
    */
    Route::resource('products', ProductController::class);
    
    // Route tambahan untuk products
    Route::patch('/products/{product}/toggle-active', [ProductController::class, 'toggleActive'])
        ->name('products.toggle-active');
    Route::post('/products/{product}/duplicate', [ProductController::class, 'duplicate'])
        ->name('products.duplicate');
    
    /*
    |--------------------------------------------------------------------------
    | RESOURCE ROUTES - Categories
    |--------------------------------------------------------------------------
    */
    Route::resource('categories', CategoryController::class);
});

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'admin']) // Middleware auth + admin
    ->group(function () {
        
        // Admin Dashboard
        Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');
        
        // Manage Users
        Route::resource('users', Admin\UserController::class);
        
        // Settings
        Route::get('/settings', [Admin\SettingController::class, 'index'])->name('settings');
        Route::put('/settings', [Admin\SettingController::class, 'update'])->name('settings.update');
    });

/*
|--------------------------------------------------------------------------
| API-LIKE ROUTES (untuk AJAX)
|--------------------------------------------------------------------------
*/

Route::prefix('ajax')->name('ajax.')->middleware('auth')->group(function () {
    Route::get('/products/search', [ProductController::class, 'ajaxSearch'])
        ->name('products.search');
    Route::post('/products/check-stock', [ProductController::class, 'checkStock'])
        ->name('products.check-stock');
});

/*
|--------------------------------------------------------------------------
| FALLBACK ROUTE (404)
|--------------------------------------------------------------------------
*/

Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});
