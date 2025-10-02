<?php

namespace App\Http\Controllers;

use App\Models\TireRequest;
use App\Models\Vehicle;
use App\Models\Tire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TireRequestController extends Controller
{
    // Show create form
    public function create()
    {
        $tires = Tire::all();
        return view('driver.tireRequestCreate', compact('tires'));
    }

    // Store request
    public function store(Request $request)
    {
        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'tire_id' => 'required|exists:tires,id',
            'damage_description' => 'required|string|max:500',
            'images.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // each image max 2MB
        ]);

        $images = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $images[] = $file->store('tire_requests', 'public');
            }
        }

        TireRequest::create([
            'user_id' => auth()->id(),
            'vehicle_id' => $validated['vehicle_id'],
            'tire_id' => $validated['tire_id'],
            'damage_description' => $validated['damage_description'],
            // store as array so Eloquent casting (json) works correctly
            'tire_images' => $images,
            'status' => 'pending',
        ]);

        return redirect()->route('driver.dashboard')
            ->with('success', 'Tire request submitted successfully!');
    }


    // List all requests for driver
    public function index()
    {
        $requests = \App\Models\TireRequest::with(['vehicle', 'tire'])
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('driver.tireRequestIndex', compact('requests'));
    }
}
