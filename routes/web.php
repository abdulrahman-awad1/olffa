<?php

use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\RegistrantController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RegisterController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| صفحات عامة
|--------------------------------------------------------------------------
*/
Route::get('/', HomeController::class . '@index')->name('home');

Route::get('/register/{gender}', [RegisterController::class, 'create'])->name('register.create');
Route::post('/register/{gender}', [RegisterController::class, 'store'])->name('register.store');

Route::get('/thank-you', function () {
    return view('thankyou', ['profile' => auth()->user()->profile]);
})->middleware('auth')->name('thankyou');

/*
|--------------------------------------------------------------------------
| دخول/خروج المستخدم العادي
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);
});
Route::post('/logout', [LoginController::class, 'destroy'])->middleware('auth')->name('logout');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

/*
|--------------------------------------------------------------------------
| لوحة تحكم الإدارة
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AdminAuthController::class, 'create'])->name('login');
        Route::post('/login', [AdminAuthController::class, 'store']);
    });

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::post('/logout', [AdminAuthController::class, 'destroy'])->name('logout');
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/registrants/{profile}', [RegistrantController::class, 'show'])->name('registrants.show');
        Route::patch('/registrants/{profile}', [RegistrantController::class, 'update'])->name('registrants.update');
    });
});
