<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TireController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\TireRequestController;
use App\Http\Controllers\DriverController;
use App\Http\Middleware\ForcePasswordChange;
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\SectionManagerController;
use App\Http\Controllers\MechanicOfficerController;
use App\Http\Controllers\TransportOfficerController;
use App\Http\Controllers\ReportController;

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


            Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
            Route::get('/reports/vehicles', [ReportController::class, 'exportVehicles'])->name('reports.vehicles');
            Route::get('/reports/drivers',  [ReportController::class, 'exportDrivers'])->name('reports.drivers');
            Route::get('/reports/suppliers',[ReportController::class, 'exportSuppliers'])->name('reports.suppliers');
            Route::get('/reports/tires',    [ReportController::class, 'exportTires'])->name('reports.tires');

            Route::get('/reports/section-manager/{status}', [ReportController::class, 'exportSectionManager'])
                ->name('reports.section_manager');

            Route::get('/reports/mechanic-officer/{status}', [ReportController::class, 'exportMechanicOfficer'])
                ->name('reports.mechanic_officer');

            Route::get('/reports/transport-officer/{status}', [ReportController::class, 'exportTransportOfficer'])
                ->name('reports.transport_officer');
    });

    /**
     * ----------------
     * DRIVER ROUTES
     * ----------------
     */
Route::prefix('driver')
    ->name('driver.')
    ->middleware(['auth'])
    ->middleware(ForcePasswordChange::class)
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'driver'])->name('dashboard');

        Route::get('/requests', [TireRequestController::class, 'index'])->name('requests.index');
        Route::get('/requests/create', [TireRequestController::class, 'create'])->name('requests.create');
        Route::post('/requests', [TireRequestController::class, 'store'])->name('requests.store');
        Route::delete('/requests/{request}', [TireRequestController::class, 'destroy'])->name('requests.destroy');

        Route::get('/vehicles/lookup', [VehicleController::class, 'lookup'])->name('vehicles.lookup');

        Route::get('/profile', [DriverController::class, 'editProfile'])->name('profile.edit');
        Route::post('/profile', [DriverController::class, 'updateProfile'])->name('profile.update');

        Route::get('/change_password', [DriverController::class, 'changePasswordForm'])->name('password.form');
        Route::post('/change_password', [DriverController::class, 'updatePassword'])->name('password.update');

        Route::get('/receipts', [DriverController::class, 'receipts'])->name('receipts');
        Route::get('/receipts/{id}/download', [DriverController::class, 'downloadReceipt'])->name('receipt.download');


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
Route::prefix('mechanic-officer')->name('mechanic_officer.')->group(function () {
    Route::get('/pending', [MechanicOfficerController::class, 'pending'])->name('pending');
    Route::post('/approve/{id}', [MechanicOfficerController::class, 'approve'])->name('approve');
    Route::post('/reject/{id}', [MechanicOfficerController::class, 'reject'])->name('reject');

    // New pages
    Route::get('/approved', [MechanicOfficerController::class, 'approved'])->name('approved');
    Route::get('/rejected', [MechanicOfficerController::class, 'rejected'])->name('rejected');

    // FIXED
    Route::get('/edit-request/{id}', [MechanicOfficerController::class, 'edit'])->name('edit_request');
    Route::put('/update-request/{id}', [MechanicOfficerController::class, 'update'])->name('update');
});


    /**
     * ----------------
     * TRANSPORT OFFICER ROUTES
     * ----------------
     */
Route::prefix('transport-officer')->name('transport_officer.')->middleware(['auth'])->group(function () {
    Route::get('/', [TransportOfficerController::class, 'pending'])->name('dashboard');

    Route::get('pending', [TransportOfficerController::class, 'pending'])->name('pending');
    Route::get('approved', [TransportOfficerController::class, 'approved'])->name('approved');
    Route::get('rejected', [TransportOfficerController::class, 'rejected'])->name('rejected');

    Route::get('edit/{id}', [TransportOfficerController::class, 'edit'])->name('edit_request');
    Route::put('update/{id}', [TransportOfficerController::class, 'update'])->name('update');
    Route::post('approve/{id}', [TransportOfficerController::class, 'approve'])->name('approve');
    Route::post('reject/{id}', [TransportOfficerController::class, 'reject'])->name('reject');

    //  Receipt Routes
    Route::get('receipt/create/{id}', [TransportOfficerController::class, 'createReceipt'])->name('receipt.create');
    Route::post('receipt/store', [TransportOfficerController::class, 'storeReceipt'])->name('receipt.store');
    Route::get('/receipts/{id}/pdf', [ReceiptController::class, 'generatePDF'])->name('transport_officer.receipts.pdf');
});

});
