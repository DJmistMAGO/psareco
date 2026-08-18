<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\FarmersController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing.index');
})->name('home');

// Guest routes dito
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Protected routes or accessible lang pag nakalogin :)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::view('/dashboard', 'admin.dashboard')->name('dashboard');
    Route::view('/inventory', 'admin.inventory')->name('inventory');
    Route::view('/scheduling', 'admin.scheduling')->name('scheduling');
    Route::view('/sales', 'admin.sales')->name('sales');
    Route::view('/reports', 'admin.reports')->name('reports');
    Route::view('/my-bookings', 'farmer.my-bookings')->name('my-bookings');

    Route::controller(FarmersController::class)->group(function() {

    });

    Route::middleware('role:admin')->controller(UserManagementController::class)
        ->prefix('user-management')
        ->group(function () {
            Route::get('/', 'index')->name('user-management.index');
            Route::post('/user-management/addUser', 'addUser')->name('user-management.adduser');
            Route::post('/user-management/{id}', 'updateUser')->name('user-management.updateUser');
            Route::post('/user-management/{id}/deactivate', 'deactivateUser')->name('user-management.deactivateUser');
            Route::post('/user-management/{id}/reactivate', 'reactivateUser')->name('user-management.reactivateUser');
            Route::post('/user-management/{id}/approve', 'approveUser')->name('user-management.approveUser');
            Route::post('/user-management/{id}/reject', 'rejectUser')->name('user-management.rejectUser');
        });
});
