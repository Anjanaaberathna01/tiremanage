<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DriverController extends Controller
{
    /**
     * -------------------
     * ADMIN FUNCTIONS
     * -------------------
     */

    // Show driver creation form
    public function create()
    {
        return view('admin.drivers.create');
    }

    // Store new driver
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:users,name',
            'email' => 'required|email|max:255|unique:users,email',
            'full_name' => 'nullable|string|max:255',
            'mobile' => 'nullable|string|max:50',
            'id_number' => 'nullable|string|max:100',
        ]);

        // Create user account for driver
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make('password123'), // default password
            'role_id' => 2, // adjust according to your role system
        ]);

        // Create driver profile linked to user
        Driver::create([
            'user_id' => $user->id,
            'full_name' => $request->full_name,
            'mobile' => $request->mobile,
            'id_number' => $request->id_number,
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Driver registered successfully!');
    }

    /**
     * -------------------
     * DRIVER FUNCTIONS
     * -------------------
     */

    // Show edit profile form
    public function editProfile()
    {
        $driver = Driver::where('user_id', Auth::id())->first();

        $profilePhoto = $driver->profile_photo && Storage::disk('public')->exists($driver->profile_photo)
            ? asset('storage/' . $driver->profile_photo)
            : asset('images/default-profile.jpg'); // fallback default

        return view('driver.edit_profile', compact('driver', 'profilePhoto'));
    }

    // Update profile
    public function updateProfile(Request $request)
    {
        $driver = Driver::where('user_id', Auth::id())->firstOrFail();

        $request->validate([
            'full_name' => 'required|string|max:255',
            'mobile' => 'required|string|max:50',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'remove_photo' => 'nullable|in:0,1',
        ]);

        $driver->full_name = $request->full_name;
        $driver->mobile = $request->mobile;

        // Remove photo
        if ($request->remove_photo == "1") {
            if ($driver->profile_photo && Storage::disk('public')->exists($driver->profile_photo)) {
                Storage::disk('public')->delete($driver->profile_photo);
            }
            $driver->profile_photo = null;
        }

        // Upload new photo
        if ($request->hasFile('profile_photo')) {
            if ($driver->profile_photo && Storage::disk('public')->exists($driver->profile_photo)) {
                Storage::disk('public')->delete($driver->profile_photo);
            }
            $path = $request->file('profile_photo')->store('drivers', 'public');
            $driver->profile_photo = $path;
        }

        $driver->save();

        return redirect()->route('driver.profile.edit')->with('success', 'Profile updated successfully!');
    }
}
