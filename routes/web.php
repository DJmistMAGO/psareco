<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing.index');
})->name('home');

// Auth routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected routes
Route::middleware('auth')->group(function () {
    Route::view('/dashboard', 'admin.dashboard')->name('dashboard');
    Route::view('/inventory', 'admin.inventory')->name('inventory');
    Route::view('/scheduling', 'admin.scheduling')->name('scheduling');
    Route::view('/sales', 'admin.sales')->name('sales');
    Route::view('/reports', 'admin.reports')->name('reports');
    Route::view('/users', 'admin.users')->name('users');
    Route::view('/my-bookings', 'farmer.my-bookings')->name('my-bookings');
});
