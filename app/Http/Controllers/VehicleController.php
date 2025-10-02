<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle;

class VehicleController extends Controller
{
    /**
     * Display all vehicles
     */
    public function index()
    {
        $vehicles = Vehicle::latest()->get(); // latest for ordering
        return view('vehicles.index', compact('vehicles'));
    }

    /**
     * Show create vehicle form
     */
    public function create()
    {
        return view('vehicles.create');
    }

    /**
     * Store new vehicle
     */
    public function store(Request $request)
    {
        $request->validate([
            'model'    => 'required|string|max:255',
            'plate_no' => 'required|string|max:255|unique:vehicles,plate_no',
            'branch'   => 'required|string|max:255',
        ]);

        Vehicle::create([
            'model'    => $request->model,
            'plate_no' => strtoupper($request->plate_no), // normalize plates
            'branch'   => $request->branch,
        ]);

        return redirect()->route('admin.vehicles.index')
            ->with('success', '✅ Vehicle added successfully.');
    }

    /**
     * Edit vehicle
     */
    public function edit(Vehicle $vehicle)
    {
        return view('vehicles.edit', compact('vehicle'));
    }

    /**
     * Update vehicle
     */
    public function update(Request $request, Vehicle $vehicle)
    {
        $request->validate([
            'model'    => 'required|string|max:255',
            'plate_no' => 'required|string|max:255|unique:vehicles,plate_no,' . $vehicle->id,
            'branch'   => 'required|string|max:255',
        ]);

        $vehicle->update([
            'model'    => $request->model,
            'plate_no' => strtoupper($request->plate_no),
            'branch'   => $request->branch,
        ]);

        return redirect()->route('admin.vehicles.index')
            ->with('success', '✅ Vehicle updated successfully.');
    }

    /**
     * Delete vehicle
     */
    public function destroy(Vehicle $vehicle)
    {
        $vehicle->delete();
        return redirect()->route('admin.vehicles.index')
            ->with('success', '🗑️ Vehicle deleted successfully.');
    }

    /**
     * Lookup vehicle by plate number (AJAX)
     */
public function lookup(Request $request)
{
    $plate = $request->query('plate_no');

    if (!$plate) {
        return response()->json(['found' => false], 400);
    }

    // normalize search
    $vehicle = Vehicle::whereRaw('LOWER(TRIM(plate_no)) = ?', [strtolower(trim($plate))])->first();

    if (!$vehicle) {
        return response()->json(['found' => false]);
    }

    return response()->json([
        'found'    => true,
        'id'       => $vehicle->id,
        'plate_no' => $vehicle->plate_no,
        'branch'   => $vehicle->branch,
        'model'    => $vehicle->model,
    ]);
}

}
