<?php

namespace App\Http\Controllers;

use App\Models\TireRequest;
use App\Models\Vehicle;
use App\Models\Tire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

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
            'tire_count' => 'required|integer|min:1',
            'damage_description' => 'required|string|max:500',
            'images.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'delivery_place_office' => 'nullable|string|max:255',
            'delivery_place_street' => 'nullable|string|max:255',
            'delivery_place_town' => 'nullable|string|max:255',
            'last_tire_replacement_date' => 'nullable|date',
            'existing_tire_make' => 'nullable|string|max:255',
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
            'tire_count' => $validated['tire_count'],
            'damage_description' => $validated['damage_description'],
            'tire_images' => $images,
            'status' => 'pending',
            'delivery_place_office' => $validated['delivery_place_office'] ?? null,
            'delivery_place_street' => $validated['delivery_place_street'] ?? null,
            'delivery_place_town' => $validated['delivery_place_town'] ?? null,
            'last_tire_replacement_date' => $validated['last_tire_replacement_date'] ?? null,
            'existing_tire_make' => $validated['existing_tire_make'] ?? null,
        ]);

        return redirect()->route('driver.dashboard')
            ->with('success', 'Tyre request submitted successfully!');
    }

    // List all requests for driver, today first
    public function index()
    {
        $today = Carbon::today();

        $requests = TireRequest::with(['vehicle', 'tire'])
            ->where('user_id', auth()->id())
            ->orderByRaw("DATE(created_at) = ? DESC", [$today->toDateString()])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('driver.tireRequestIndex', compact('requests'));
    }

    // Delete a tire request (only if pending)
    public function destroy(TireRequest $request)
    {
        if ($request->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        if ($request->status !== 'pending') {
            return redirect()->route('driver.requests.index')
                ->with('error', 'Only pending requests can be deleted.');
        }

        // Delete images from storage
        if ($request->tire_images && is_array($request->tire_images)) {
            foreach ($request->tire_images as $img) {
                \Storage::disk('public')->delete($img);
            }
        }

        $request->delete();

        return redirect()->route('driver.requests.index')
            ->with('success', 'Tyre request deleted successfully.');
    }
}
