<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tire;
use App\Models\Supplier;

class TireController extends Controller
{
    // Display all tires
    public function index()
    {
        $tires = Tire::with('supplier')->get();
        return view('tires.index', compact('tires'));
    }

    // Show form to create a new tire
    public function create()
    {
        $suppliers = Supplier::all();
        return view('tires.create', compact('suppliers'));
    }

    // Store a new tire
    public function store(Request $request)
    {
        $request->validate([
            'brand'       => 'required|string|max:255',
            'size'        => 'required|string|max:255',
            'supplier_id' => 'required|exists:suppliers,id',
        ]);

        Tire::create([
            'brand'       => $request->brand,
            'size'        => $request->size,
            'supplier_id' => $request->supplier_id,
        ]);

        return redirect()->route('admin.tires.index')
            ->with('success', 'Tire added successfully.');
    }

    // Show form to edit an existing tire
    public function edit(Tire $tire)
    {
        $suppliers = Supplier::all();
        return view('tires.edit', compact('tire', 'suppliers'));
    }

    // Update an existing tire
    public function update(Request $request, Tire $tire)
    {
        $request->validate([
            'brand'       => 'required|string|max:255',
            'size'        => 'required|string|max:255',
            'supplier_id' => 'required|exists:suppliers,id',
        ]);

        $tire->update([
            'brand'       => $request->brand,
            'size'        => $request->size,
            'supplier_id' => $request->supplier_id,
        ]);

        return redirect()->route('admin.tires.index')
            ->with('success', 'Tire updated successfully.');
    }

    // Delete a tire
    public function destroy(Tire $tire)
    {
        $tire->delete();
        return redirect()->route('admin.tires.index')
            ->with('success', 'Tire deleted successfully.');
    }
}
