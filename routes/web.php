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
use App\Http\Controllers\MechanicOfficerController;
use App\Http\Controllers\TransportOfficerController;

// Redirect root to login
Route::get('/', fn() => redirect()->route('login'));

// ------------------ AUTHENTICATION ------------------
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ------------------ PROTECTED ROUTES ------------------
Route::middleware(['auth'])->group(function () {

    /**
     * ----------------
     * ADMIN ROUTES
     * ----------------
     */
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');

        // Resources
        Route::resource('vehicles', VehicleController::class);
        Route::resource('tires', TireController::class);
        Route::resource('suppliers', SupplierController::class);

        // Requests
        Route::get('/requests/pending', [DashboardController::class, 'pendingRequests'])->name('request.pending');

        // Driver management
        Route::get('/drivers/create', [DriverController::class, 'create'])->name('drivers.create');
        Route::post('/drivers', [DriverController::class, 'store'])->name('drivers.store');
        Route::delete('/drivers/{id}', [DriverController::class, 'destroy'])->name('drivers.destroy');
    });

    /**
     * ----------------
     * DRIVER ROUTES
     * ----------------
     */
    Route::prefix('driver')->name('driver.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'driver'])->name('dashboard');

        Route::get('/requests', [TireRequestController::class, 'index'])->name('requests.index');
        Route::get('/requests/create', [TireRequestController::class, 'create'])->name('requests.create');
        Route::post('/requests', [TireRequestController::class, 'store'])->name('requests.store');
        Route::delete('/requests/{request}', [TireRequestController::class, 'destroy'])->name('requests.destroy');

        Route::get('/vehicles/lookup', [VehicleController::class, 'lookup'])->name('vehicles.lookup');

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
    Route::get('/pending', [SectionManagerController::class, 'pending'])->name('requests.pending');
    Route::get('/approved', [SectionManagerController::class, 'approved'])->name('requests.approved_list');
    Route::get('/rejected', [SectionManagerController::class, 'rejected'])->name('requests.rejected_list');
    Route::post('/{id}/approve', [SectionManagerController::class, 'approve'])->name('requests.approve');
    Route::post('/{id}/reject', [SectionManagerController::class, 'reject'])->name('requests.reject');
    Route::get('/requests/{id}/edit', [SectionManagerController::class, 'edit'])->name('requests.edit');
    Route::put('/requests/{id}', [SectionManagerController::class, 'update'])->name('requests.update');
    Route::get('/requests/search', [SectionManagerController::class, 'search'])->name('requests.search');

    // Vehicles
    Route::resource('vehicles', VehicleController::class)->names([
        'index'   => 'vehicles.index',
        'create'  => 'vehicles.create',
        'store'   => 'vehicles.store',
        'edit'    => 'vehicles.edit',
        'update'  => 'vehicles.update',
        'destroy' => 'vehicles.destroy',
        'show'    => 'vehicles.show',
    ]);

    // Drivers
    Route::get('/drivers', [SectionManagerController::class, 'drivers'])->name('drivers.index');
    Route::get('/drivers/create', [SectionManagerController::class, 'createDriver'])->name('drivers.create');
    Route::post('/drivers', [SectionManagerController::class, 'storeDriver'])->name('drivers.store');
    Route::delete('/drivers/{id}', [SectionManagerController::class, 'destroyDriver'])->name('drivers.destroy');
});



    /**
     * ----------------
     * MECHANIC OFFICER ROUTES
     * ----------------
     */
Route::middleware(['auth'])->prefix('mechanic-officer')->name('mechanic_officer.')->group(function () {
    Route::get('/pending', [MechanicOfficerController::class, 'pending'])->name('pending');
    Route::post('/approve/{id}', [MechanicOfficerController::class, 'approve'])->name('approve');
    Route::post('/reject/{id}', [MechanicOfficerController::class, 'reject'])->name('reject');
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
