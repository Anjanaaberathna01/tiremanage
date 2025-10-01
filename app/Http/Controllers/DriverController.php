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
// Show edit profile form
public function editProfile()
{
$driver = Driver::where('user_id', Auth::id())->first();
return view('driver.edit_profile', compact('driver'));
}

// Update profile (full_name, mobile, profile_photo)
public function updateProfile(Request $request)
{
$driver = Driver::where('user_id', Auth::id())->firstOrFail();

$request->validate([
'full_name' => 'required|string|max:255',
'mobile' => 'required|string|max:50',
'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
'remove_photo' => 'nullable|in:0,1',
]);

// Update text fields
$driver->full_name = $request->full_name;
$driver->mobile = $request->mobile;

// Handle profile photo removal
if ($request->remove_photo == "1") {
if ($driver->profile_photo && Storage::disk('public')->exists($driver->profile_photo)) {
Storage::disk('public')->delete($driver->profile_photo);
}
$driver->profile_photo = null; // fallback will load default
}

// Handle new profile photo upload
if ($request->hasFile('profile_photo')) {
// Delete old file if exists
if ($driver->profile_photo && Storage::disk('public')->exists($driver->profile_photo)) {
Storage::disk('public')->delete($driver->profile_photo);
}

// Store new file
$path = $request->file('profile_photo')->store('drivers', 'public');
$driver->profile_photo = $path;
}

$driver->save();

return redirect()->route('driver.profile.edit')->with('success', 'Profile updated successfully!');
}
}