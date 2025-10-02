<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TireController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\TireRequestController;
use App\Http\Controllers\DriverController;

// Root redirect
Route::get('/', fn() => redirect()->route('login'));

// Authentication
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Protected routes
Route::middleware(['auth'])->group(function () {

    // Admin
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');
        Route::resource('vehicles', VehicleController::class);
        Route::resource('tires', TireController::class);
        Route::resource('suppliers', SupplierController::class);
        Route::get('/drivers/create', [DriverController::class, 'create'])->name('drivers.create');
        Route::post('/drivers', [DriverController::class, 'store'])->name('drivers.store');
    });

    // Driver
    Route::prefix('driver')->name('driver.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'driver'])->name('dashboard');
        Route::get('/requests', [TireRequestController::class, 'index'])->name('requests.index');
        Route::get('/requests/create', [TireRequestController::class, 'create'])->name('requests.create');
        Route::post('/requests', [TireRequestController::class, 'store'])->name('requests.store');
        Route::get('/profile', [DriverController::class, 'editProfile'])->name('profile.edit');
        Route::post('/profile', [DriverController::class, 'updateProfile'])->name('profile.update');
    });

    // Section Manager
    Route::prefix('section-manager')->name('section_manager.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'sectionManager'])->name('dashboard');
    });


    // Mechanic Officer
    Route::prefix('mechanic-officer')->name('mechanic_officer.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'mechanicOfficer'])->name('dashboard');
    });

    // Transport Officer
    Route::prefix('transport-officer')->name('transport_officer.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'transportOfficer'])->name('dashboard');
    });

});