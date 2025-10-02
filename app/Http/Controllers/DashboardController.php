<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request; // Laravel HTTP Request
use App\Models\User;
use App\Models\Supplier;
use App\Models\Vehicle;
use App\Models\Tire;
use App\Models\TireRequest; // your renamed Request model
use App\Models\Driver;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Admin Dashboard
     */

    public function admin()
    {
        $vehicles = Vehicle::orderBy('model', 'asc')->get();
        $tires = Tire::with('supplier')->get();
        $suppliers = Supplier::all();
        $drivers = Driver::with('user')->get();

        return view('admin.dashboard', [
            'vehicles' => $vehicles,
            'vehicles_count' => $vehicles->count(),
            'tires' => $tires,
            'tires_count' => $tires->count(),
            'suppliers' => $suppliers,
            'suppliers_count' => $suppliers->count(),
            'drivers' => $drivers,
            'drivers_count' => $drivers->count(),
            'pending_requests' => 0 // replace with actual pending requests if any
        ]);
    }




    /**
     * Driver Dashboard
     */
    public function driver()
    {
        $user = Auth::user();
        $requests = TireRequest::where('user_id', $user->id)->get();

        return view('dashboard.driver', compact('requests'));
    }

    /**
     * Section Manager Dashboard
     */
public function sectionManager()
{
    $pendingRequests = TireRequest::where('status', 'pending')->get();

    // Note: view path uses dot notation for subfolders
    return view('dashboard.section_manager.section_manager', compact('pendingRequests'));
}


    /**
     * Mechanic Officer Dashboard
     */
    public function mechanicOfficer()
    {
        $tires = Tire::all();
        $vehicles = Vehicle::all();
        return view('dashboard.mechanic_officer', compact('tires', 'vehicles'));
    }

    /**
     * Transport Officer Dashboard
     */
    public function transportOfficer()
    {
        $approvals = TireRequest::where('status', 'approved')->get();
        return view('dashboard.transport_officer', compact('approvals'));
    }
}