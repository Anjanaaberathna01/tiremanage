<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle;

class VehicleController extends Controller
{
    public function index()
    {
        $vehicles = Vehicle::all();
        return view('vehicles.index', compact('vehicles'));
    }

    public function create()
    {
        return view('vehicles.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'model' => 'required|string|max:255',
            'plate_no' => 'required|string|max:255|unique:vehicles,plate_no',
        ]);

        Vehicle::create($request->only(['model', 'plate_no']));

        return redirect()->route('admin.vehicles.index')
            ->with('success', 'Vehicle added successfully.');
    }

    public function edit(Vehicle $vehicle)
    {
        return view('vehicles.edit', compact('vehicle'));
    }

    public function update(Request $request, Vehicle $vehicle)
    {
        $request->validate([
            'model' => 'required|string|max:255',
            'plate_no' => 'required|string|max:255|unique:vehicles,plate_no,' . $vehicle->id,
        ]);

        $vehicle->update($request->only(['model', 'plate_no']));

        return redirect()->route('admin.vehicles.index')
            ->with('success', 'Vehicle updated successfully.');
    }

    public function destroy(Vehicle $vehicle)
    {
        $vehicle->delete();
        return redirect()->route('admin.vehicles.index')
            ->with('success', 'Vehicle deleted successfully.');
    }
}
