<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Driver;
use App\Models\User;
use App\Models\Role;
use App\Models\TireRequest;
use App\Models\Receipt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DriverController extends Controller
{
    // Show driver creation form
    public function create()
    {
        $user = Auth::user();

        $userRole = strtolower(str_replace(' ', '_', $user->role->name ?? ''));

        if (!in_array($userRole, ['admin', 'section_manager'])) {
            abort(403, 'Unauthorized');
        }

        return view('admin.drivers.create');
    }

    // Store new driver
    public function store(Request $request)
    {
        $user = Auth::user();

        $userRole = strtolower(str_replace(' ', '_', $user->role->name ?? ''));

        if (!in_array($userRole, ['admin', 'section_manager'])) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'name' => 'required|string|unique:users,name',
            'email' => 'required|email|unique:users,email',
            'full_name' => 'nullable|string|max:255',
            'mobile' => ['nullable', 'string', 'max:50', 'regex:/^(0\d{9}|\+?94\d{9})$/'],
            'id_number' => 'nullable|string|max:50',
        ]);

        // Get driver role
        $driverRole = Role::where('name', 'driver')->first();
        if (!$driverRole) {
            return redirect()->back()->with('error', 'Driver role not found. Please create it first.');
        }

        // Create driver user
        $newUser = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make('12345678'),
            'role_id' => $driverRole->id,
            'must_change_password' => true,
        ]);

        // Create Driver profile record
        Driver::create([
            'user_id' => $newUser->id,
            'name' => $newUser->name,
            'email' => $newUser->email,
            'full_name' => $request->full_name,
            'mobile' => $request->mobile,
            'id_number' => $request->id_number,
        ]);

        return redirect()->route(
            $user->role->name === 'admin' ? 'admin.drivers.create' : 'section_manager.drivers.create'
        )->with('success', 'Driver created successfully.');
    }

    // Show driver edit profile form
    public function editProfile()
    {
        $driver = Driver::where('user_id', Auth::id())->firstOrFail();

        $profilePhoto = $driver->profile_photo && Storage::disk('public')->exists($driver->profile_photo)
            ? asset('storage/' . $driver->profile_photo)
            : asset('assets/images/default-profile.jpg'); //  default path

        return view('driver.edit_profile', compact('driver', 'profilePhoto'));
    }

    // Update driver profile
    public function updateProfile(Request $request)
    {
        $driver = Driver::where('user_id', Auth::id())->firstOrFail();
        $user = $driver->user;

        // Normalize mobile input
        $rawMobile = $request->input('mobile');
        $normalizedMobile = $rawMobile !== null ? preg_replace('/[\s\-\(\)]/', '', $rawMobile) : null;
        $request->merge(['mobile' => $normalizedMobile]);

        $request->validate([
            'name' => 'required|string|unique:users,name,' . $user->id,
            'full_name' => 'required|string|max:255',
            'mobile' => ['nullable', 'string', 'max:50', 'regex:/^(0\d{9}|\+?94\d{9})$/'],
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'remove_photo' => 'nullable|in:0,1',
        ], [
            'mobile.regex' => 'Mobile must be 10 digits starting with 0 (e.g. 0711234567) or include country code 94 with 9 subscriber digits (e.g. +94711234567).',
        ]);

        // Update username
        $user->name = $request->name;
        $user->save();

        // Update driver profile
        $driver->full_name = $request->full_name;
        $driver->mobile = $request->mobile;

        // Remove existing photo
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
            $driver->profile_photo = $request->file('profile_photo')->store('drivers', 'public');
        }

        $driver->save();

        return redirect()->route('driver.profile.edit')->with('success', 'Profile updated successfully!');
    }

    public function destroy($id)
{
    $driver = Driver::findOrFail($id);

    // Delete associated user if exists
    if ($driver->user) {
        $driver->user->delete();
    }

    $driver->delete();

    return redirect()->route('admin.dashboard')->with('success', 'Driver deleted successfully.');
}

public function receipts()
{
    Receipt::whereHas('tireRequest', function ($query) {
            $query->where('user_id', auth()->id());
        })
        ->where('is_read', false)
        ->update(['is_read' => true]);

$receipts = Receipt::with(['supplier', 'tireRequest.vehicle'])
    ->whereHas('tireRequest', function ($query) {
        $query->where('user_id', auth()->id());
    })
    ->orderByDesc('created_at')
    ->get();


    return view('driver.receipts', compact('receipts'));
}

public function downloadReceipt($id)
{
    $receipt = Receipt::with(['supplier', 'tireRequest.vehicle', 'user'])
        ->findOrFail($id);

    $pdf = Pdf::loadView('pdf.receipt', compact('receipt'));

    $filename = 'Receipt_' . $receipt->id . '.pdf';

    return $pdf->download($filename);
}

}