<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FarmersController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\MachineryController;
use App\Http\Controllers\OfficerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\UserManagementController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('landing.index');
})->name('home');

// Guest routes dito
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);


// Protected routes or accessible lang pag nakalogin :)
Route::middleware('auth')->group(function () {

    Route::view('/machinery-bookings', 'admin.machinery-booking')->name('machinery-booking');
    Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');

    Route::controller(DashboardController::class)->prefix('dashboard')
        ->group(function () {
            Route::get('/', 'index')->name('dashboard.index');
        });

    Route::controller(FarmersController::class)
        ->prefix('farmers')
        ->group(function () {
            Route::get('/index', 'index')->name('farmers.index');
            Route::get('/my-bookings', 'myBookings')->name('farmers.myBookings');
            Route::get('/products', 'products')->name('farmers.products');
            Route::post('/book-machinery', 'store')->name('farmers.bookMachinery');
            Route::get('/booking-details/{id}', 'bookingDetails')->name('farmers.bookingDetails');
            Route::put('/update-bookingSlot/{id}', 'updateBookingSlot')->name('farmers.updateBookingSlot');
            Route::put('/complete-booking/{id}', 'completeBooking')->name('farmers.completeBooking');
            Route::delete('/delete/{booking}', 'deleteBooking')->name('farmers.deleteBooking');

        });

    Route::middleware('role:officer')->controller(OfficerController::class)
        ->prefix('officer')
        ->group(function () {
            Route::get('/booking/index', 'indexBooking')->name('officer.index-booking');
            Route::put('/booking/approve/{id}', 'approveBooking')->name('officer.approve-booking');
            Route::put('/booking/decline/{id}', 'declineBooking')->name('officer.decline-booking');

        });

    Route::controller(CalendarController::class)
        ->prefix('calendar')
        ->group(function () {
            Route::get('/', 'index')->name('booking.calendar');
            Route::get('/schedule', 'calendarSchedule')->name('schedule.booking-calendar');
        });


    Route::controller(MachineryController::class)
    ->prefix('machinery')
    ->group(function () {
        Route::get('/index', 'index')->name('machinery.index');
        Route::post('/store', 'store')->name('machinery.store');
        Route::put('/{id}', 'update')->name('machinery.update');
        Route::delete('/{id}', 'destroy')->name('machinery.destroy');
    });

    Route::controller(InventoryController::class)
        ->prefix('inventory')
        ->group(function () {
            Route::get('/', 'index')->name('inventory.index');
            Route::post('/addProduct', 'addProduct')->name('inventory.addProduct');
            Route::get('/trash', 'trash')->name('inventory.trash');
            Route::put('/{inventory}', 'updateProduct')->name('inventory.updateProduct');
            Route::delete('/{inventory}', 'deleteProduct')->name('inventory.deleteProduct');
            Route::post('/{id}/restore', 'restoreProduct')->name('inventory.restoreProduct');
            Route::delete('/{id}/force-delete', 'forceDeleteProduct')->name('inventory.forceDeleteProduct');

        });

    Route::middleware('role:officer')->controller(SalesController::class)->prefix('sales')
        ->group(function () {
            Route::get('/', 'index')->name('sales.index');
            Route::post('/checkout', 'checkout')->name('sales.checkout');
            Route::post('/export', 'export')->name('sales.export');
        });

    Route::middleware('role:admin|officer')->controller(ReportController::class)->prefix('reports')
        ->group(function () {
            Route::get('/', 'index')->name('reports.index');
            Route::get('/generate', 'generate')->name('reports.generate');
            Route::get('/preview', 'preview')->name('reports.preview');

        });

    Route::middleware('role:admin')->controller(UserManagementController::class)
        ->prefix('user-management')
        ->group(function () {
            Route::get('/', 'index')->name('user-management.index');
            Route::get('/export', 'exportCsv')->name('user-management.export');
            Route::post('/addUser', 'addUser')->name('user-management.adduser');
            Route::put('/{id}', 'updateUser')->name('user-management.updateUser');
            Route::post('/{id}/deactivate', 'deactivateUser')->name('user-management.deactivateUser');
            Route::post('/{id}/reactivate', 'reactivateUser')->name('user-management.reactivateUser');
            Route::post('/{id}/delete', 'deleteUser')->name('user-management.deleteUser');
            // Route::post('/{id}/approve', 'approveUser')->name('user-management.approveUser');
            // Route::post('/{id}/reject', 'rejectUser')->name('user-management.rejectUser');
        });


    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
