<?php

use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\RegistrantController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ThankYouController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| صفحات عامة
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');

// {gender} = male | female (أي قيمة تانية بترجّع 404 تلقائيًا بسبب الـ enum)
Route::middleware('guest')->prefix('register/{gender}')->name('register.')->group(function () {
    Route::get('/', [RegisterController::class, 'create'])->name('create');
    Route::post('/', [RegisterController::class, 'store'])->middleware('throttle:10,1')->name('store');
});

Route::get('/thank-you', ThankYouController::class)->middleware(['auth', 'no-store'])->name('thankyou');

/*
|--------------------------------------------------------------------------
| دخول/خروج المستخدم العادي
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->middleware('throttle:5,1');
});

Route::middleware(['auth', 'no-store'])->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

/*
|--------------------------------------------------------------------------
| لوحة تحكم الإدارة
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AdminAuthController::class, 'create'])->name('login');
        Route::post('/login', [AdminAuthController::class, 'store'])->middleware('throttle:5,1');
    });

    Route::middleware(['auth', 'admin', 'no-store'])->group(function () {
        Route::post('/logout', [AdminAuthController::class, 'destroy'])->name('logout');
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/registrants/{profile}', [RegistrantController::class, 'show'])->name('registrants.show');
        Route::patch('/registrants/{profile}', [RegistrantController::class, 'update'])->name('registrants.update');
    });
});
