<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Supplier;
use App\Models\Vehicle;
use App\Models\Tire;
use App\Models\TireRequest;
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

        //  Count pending requests
        $pending_requests = TireRequest::where('status', 'pending')->count();

        return view('admin.dashboard', [
            'vehicles' => $vehicles,
            'vehicles_count' => $vehicles->count(),
            'tires' => $tires,
            'tires_count' => $tires->count(),
            'suppliers' => $suppliers,
            'suppliers_count' => $suppliers->count(),
            'drivers' => $drivers,
            'drivers_count' => $drivers->count(),
            'pending_requests' => $pending_requests //  now real count
        ]);
    }

    /**
     * Admin view pending requests
     */
/**
 * ---------------- ADMIN: PENDING REQUESTS OVERVIEW ----------------
 */
public function pendingRequests()
{
    // Pending at Section Manager level
    $sectionManagerRequests = \App\Models\TireRequest::where('status', \App\Models\Approval::STATUS_PENDING)
        ->where('current_level', \App\Models\Approval::LEVEL_SECTION_MANAGER)
        ->with(['user', 'vehicle', 'tire'])
        ->orderByDesc('created_at')
        ->get();

    // Pending at Mechanic Officer level
    $mechanicOfficerRequests = \App\Models\TireRequest::where('status', \App\Models\Approval::STATUS_PENDING_MECHANIC)
        ->where('current_level', \App\Models\Approval::LEVEL_MECHANIC_OFFICER)
        ->with(['user', 'vehicle', 'tire'])
        ->orderByDesc('created_at')
        ->get();

    // Pending at Transport Officer level
    $transportOfficerRequests = \App\Models\TireRequest::where('status', \App\Models\Approval::STATUS_PENDING_TRANSPORT)
        ->where('current_level', \App\Models\Approval::LEVEL_TRANSPORT_OFFICER)
        ->with(['user', 'vehicle', 'tire'])
        ->orderByDesc('created_at')
        ->get();

    return view('admin.pending_requests', compact(
        'sectionManagerRequests',
        'mechanicOfficerRequests',
        'transportOfficerRequests'
    ));
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

        return view('dashboard.section_manager.section_manager', compact('pendingRequests'));
    }

    /**
     * Mechanic Officer Dashboard
     */
    public function mechanicOfficer()
    {
        $tires = Tire::all();
        $vehicles = Vehicle::all();
        return view('dashboard.mechanic_officer.pending', compact('tires', 'vehicles'));
    }

    /**
     * Transport Officer Dashboard
     */
   /* public function transportOfficer()
    {
        $approvals = TireRequest::where('status', 'approved')->get();
        return view('dashboard.transport_officer', compact('approvals'));
    }*/
}