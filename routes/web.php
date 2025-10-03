<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TireController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\TireRequestController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\SectionManagerController;

// Root redirect
Route::get('/', fn() => redirect()->route('login'));

// Authentication
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Protected routes
Route::middleware(['auth'])->group(function () {

    /**
     * ----------------
     * ADMIN ROUTES
     * ----------------
     */
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');

        Route::resource('vehicles', VehicleController::class);
        Route::resource('tires', TireController::class);
        Route::resource('suppliers', SupplierController::class);
        Route::get('/requests/pending', [DashboardController::class, 'pendingRequests'])->name('requests.pending');
        Route::get('/pending-requests', [DashboardController::class, 'pendingRequests'])
            ->name('pending.requests');


        // Driver management
    Route::get('/drivers/create', [DriverController::class, 'create'])->name('drivers.create');
    Route::post('/drivers', [DriverController::class, 'store'])->name('drivers.store');
    });

    /**
     * ----------------
     * DRIVER ROUTES
     * ----------------
     */
    Route::prefix('driver')->name('driver.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'driver'])->name('dashboard');

        // Tire Requests
        Route::get('/requests', [TireRequestController::class, 'index'])->name('requests.index');
        Route::get('/requests/create', [TireRequestController::class, 'create'])->name('requests.create');
        Route::post('/requests', [TireRequestController::class, 'store'])->name('requests.store');

        // Delete tire request
        Route::delete('/requests/{request}', [TireRequestController::class, 'destroy'])->name('requests.destroy');

        // Vehicle lookup
        Route::get('/vehicles/lookup', [VehicleController::class, 'lookup'])->name('vehicles.lookup');

        // Profile routes
        Route::get('/profile', [DriverController::class, 'editProfile'])->name('profile.edit');
        Route::post('/profile', [DriverController::class, 'updateProfile'])->name('profile.update');
    });
/**
 * ----------------
 * SECTION MANAGER ROUTES
 * ----------------
 */
Route::prefix('section-manager')->name('section_manager.')->group(function () {
    Route::get('/dashboard', [SectionManagerController::class, 'index'])->name('dashboard');

    // Requests
    Route::get('/requests/approved', [SectionManagerController::class, 'approved'])->name('requests.approved_list');
    Route::get('/requests/rejected', [SectionManagerController::class, 'rejected'])->name('requests.rejected_list');
    Route::post('/requests/{id}/approve', [SectionManagerController::class, 'approve'])->name('requests.approve');
    Route::post('/requests/{id}/reject', [SectionManagerController::class, 'reject'])->name('requests.reject');
    Route::get('/requests/{id}/edit', [SectionManagerController::class, 'edit'])->name('requests.edit');
    Route::put('/requests/{id}', [SectionManagerController::class, 'update'])->name('requests.update');
    Route::get('/requests/search', [SectionManagerController::class, 'search'])->name('requests.search');

// Driver management for Section Manager
Route::get('/drivers/create', [DriverController::class, 'create'])->name('drivers.create');
Route::post('/drivers', [DriverController::class, 'store'])->name('drivers.store');
Route::get('/drivers', [SectionManagerController::class, 'drivers'])->name('drivers.index');
Route::delete('/drivers/{id}', [SectionManagerController::class, 'destroy'])->name('drivers.destroy'); 


});


    /**
     * ----------------
     * MECHANIC OFFICER ROUTES
     * ----------------
     */
    Route::prefix('mechanic-officer')->name('mechanic_officer.')->group(function () {
        // Use MechanicOfficerController for mechanic workflows
        Route::get('/dashboard', [App\Http\Controllers\MechanicOfficerController::class, 'index'])->name('dashboard');
        Route::get('/requests/approved', [App\Http\Controllers\MechanicOfficerController::class, 'approved'])->name('requests.approved_list');
        Route::get('/requests/rejected', [App\Http\Controllers\MechanicOfficerController::class, 'rejected'])->name('requests.rejected_list');
        Route::post('/requests/{id}/approve', [App\Http\Controllers\MechanicOfficerController::class, 'approve'])->name('requests.approve');
        Route::post('/requests/{id}/reject', [App\Http\Controllers\MechanicOfficerController::class, 'reject'])->name('requests.reject');
    });

    /**
     * ----------------
     * TRANSPORT OFFICER ROUTES
     * ----------------
     */
    Route::prefix('transport-officer')->name('transport_officer.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'transportOfficer'])->name('dashboard');
    });

});