<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\TireRequest;
use App\Models\Approval;
use App\Models\Driver;
use App\Models\User;
use App\Models\Vehicle; 

class SectionManagerController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $user = auth()->user();
            if (!$user || !$user->role) {
                abort(403, 'Unauthorized.');
            }
            $role = strtolower(str_replace([' ', '-'], '_', trim($user->role->name)));
            if ($role !== 'section_manager') {
                abort(403, 'Access restricted to Section Manager.');
            }
            return $next($request);
        });
    }
    // Show dashboard with pending requests
public function index()
{
    $pendingRequests = TireRequest::with(['user', 'vehicle', 'tire'])
        ->where('status', 'pending')
        ->orderByDesc('created_at')
        ->get();

    return view('dashboard.section_manager.section_manager', compact('pendingRequests'));
}



public function search(Request $request)
{
    $search = $request->input('search');

    $requests = TireRequest::with(['user', 'vehicle', 'tire'])
        ->whereHas('user', function ($q) use ($search) {
            $q->where('name', 'like', '%' . $search . '%');
        })
        ->orderByDesc('created_at')
        ->get();

    return view('dashboard.section_manager.search_results', compact('requests', 'search'));
}


    // Show approved
    public function approved()
    {
        $approved = TireRequest::with(['user', 'vehicle', 'tire'])
            ->where('status', 'approved')
            ->orderByDesc('updated_at')
            ->get();

        return view('dashboard.section_manager.approved', ['requests' => $approved]);
    }

    // Show rejected
    public function rejected()
    {
        $rejected = TireRequest::with(['user', 'vehicle', 'tire'])
            ->where('status', 'rejected')
            ->orderByDesc('updated_at')
            ->get();

        return view('dashboard.section_manager.rejected', ['requests' => $rejected]);
    }

    // Approve action
    public function approve($id)
    {
        $req = TireRequest::findOrFail($id);
        $req->status = 'approved';
        $req->save();

        // Record section manager approval
        Approval::create([
            'request_id' => $req->id,
            'approved_by' => auth()->id(),
            'level' => 'section_manager',
            'status' => 'approved',
            'remarks' => null,
        ]);
        return redirect()->back()->with('success', 'Request approved.');
    }

    // Reject action
    public function reject($id)
    {
        $req = TireRequest::findOrFail($id);
        $req->status = 'rejected';
        $req->save();

        // Record section manager rejection
        Approval::create([
            'request_id' => $req->id,
            'approved_by' => auth()->id(),
            'level' => 'section_manager',
            'status' => 'rejected',
            'remarks' => null,
        ]);
        return redirect()->back()->with('success', 'Request rejected.');
    }

    // Edit request
    public function edit($id)
    {
        $request = TireRequest::with(['user', 'vehicle', 'tire'])->findOrFail($id);
        return view('dashboard.section_manager.edit_request', compact('request'));
    }

    // Update request
    public function update(Request $request, $id)
    {
        $req = TireRequest::findOrFail($id);

        $request->validate([
            'damage_description' => 'nullable|string|max:500',
            'status' => 'in:approved,rejected,pending',
        ]);

        $req->damage_description = $request->damage_description;
        if ($request->has('status')) {
            $req->status = $request->status;
        }
        $req->save();

        // 🔹 Redirect back to Section Manager Dashboard
        return redirect()->route('section_manager.dashboard')
            ->with('success', 'Request updated successfully.');
    }

        public function drivers()
    {
        $drivers = Driver::with('user')->get();
        return view('dashboard.section_manager.drivers.index', compact('drivers'));
    }
public function destroy($id)
{
    $driver = Driver::findOrFail($id);

    if ($driver->user) {
        $driver->user->delete(); // delete linked user account
    }

    $driver->delete();

    return redirect()->route('section_manager.drivers.index')
                     ->with('success', 'Driver deleted successfully.');
}

 public function vehicles(Request $request)
    {
        $search = $request->input('search');

        $vehicles = Vehicle::query();

        if ($search) {
            $vehicles->where('plate_no', 'like', "%$search%");
        }

        $vehicles = $vehicles->orderByDesc('id')->get();

        return view('dashboard.section_manager.vehicles.index', compact('vehicles', 'search'));
    }

    // Delete vehicle
    public function destroyVehicle($id)
    {
        $vehicle = Vehicle::findOrFail($id);
        $vehicle->delete();

        return redirect()->route('section_manager.vehicles.index')
                         ->with('success', 'Vehicle deleted successfully.');
    }

}