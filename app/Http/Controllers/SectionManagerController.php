<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TireRequest;
use App\Models\Approval;
use App\Models\Vehicle;
use App\Models\Driver;

class SectionManagerController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $user = auth()->user();
            if (!$user || !$user->role || strtolower($user->role->name) !== 'section manager') {
                abort(403, 'Access restricted to Section Manager.');
            }
            return $next($request);
        });
    }

    /** ---------------- DASHBOARD ---------------- */
    public function index()
    {
        $pendingRequests = TireRequest::where('status', Approval::STATUS_PENDING)
            ->where('current_level', Approval::LEVEL_SECTION_MANAGER)
            ->with(['user', 'vehicle', 'tire'])
            ->orderByDesc('created_at')
            ->get();

        return view('dashboard.section_manager.section_manager', compact('pendingRequests'));
    }
public function pending()
{
    return $this->index();
}



    /** ---------------- REQUEST APPROVALS ---------------- */
    public function approved()
    {
        $approvedRecords = Approval::where('level', Approval::LEVEL_SECTION_MANAGER)
            ->where('status', Approval::STATUS_APPROVED)
            ->with('request.user', 'request.vehicle', 'request.tire')
            ->orderByDesc('updated_at')
            ->get();

        return view('dashboard.section_manager.approved', compact('approvedRecords'));
    }

    public function rejected()
    {
        $requests = TireRequest::where('status', Approval::STATUS_REJECTED)
            ->with('user', 'vehicle', 'tire')
            ->orderByDesc('updated_at')
            ->get();

        return view('dashboard.section_manager.rejected', compact('requests'));
    }

    /** Approve */
    public function approve($id)
    {
        $requestItem = TireRequest::findOrFail($id);

        $requestItem->update([
            'status' => Approval::STATUS_APPROVED,
            'current_level' => Approval::LEVEL_SECTION_MANAGER,
        ]);

        Approval::updateOrCreate(
            ['request_id' => $requestItem->id, 'level' => Approval::LEVEL_SECTION_MANAGER],
            ['approved_by' => auth()->id(), 'status' => Approval::STATUS_APPROVED]
        );

        return back()->with('success', '✅ Request approved successfully.');
    }

    /** Reject */
    public function reject($id)
    {
        $requestItem = TireRequest::findOrFail($id);

        $requestItem->update([
            'status' => Approval::STATUS_REJECTED,
            'current_level' => Approval::LEVEL_SECTION_MANAGER,
        ]);

        Approval::updateOrCreate(
            ['request_id' => $requestItem->id, 'level' => Approval::LEVEL_SECTION_MANAGER],
            ['approved_by' => auth()->id(), 'status' => Approval::STATUS_REJECTED]
        );

        return back()->with('success', '❌ Request rejected successfully.');
    }

    /** Edit and Update Request */
    public function edit($id)
    {
        $requestItem = TireRequest::with(['user', 'vehicle', 'tire', 'approvals'])->findOrFail($id);
        return view('dashboard.section_manager.edit_request', compact('requestItem'));
    }


public function update(Request $req, $id)
{
    $requestItem = TireRequest::findOrFail($id);

    $requestItem->update([
        'damage_description' => $req->damage_description,
        'status' => $req->status,
        'current_level' => Approval::LEVEL_SECTION_MANAGER,
    ]);

    Approval::updateOrCreate(
        ['request_id' => $requestItem->id, 'level' => Approval::LEVEL_SECTION_MANAGER],
        [
            'approved_by' => auth()->id(),
            'status' => match ($req->status) {
                'approved' => Approval::STATUS_APPROVED,
                'rejected' => Approval::STATUS_REJECTED,
                default => Approval::STATUS_PENDING,
            },
            'remarks' => $req->remarks ?? null,
        ]
    );

    return redirect()->route('section_manager.requests.pending')
        ->with('success', '✅ Request updated successfully.');
}

    /** Search Requests */
public function search(Request $request)
{
    $search = trim($request->input('search'));

    // Redirect back if search is empty
    if (empty($search)) {
        return redirect()->route('section_manager.dashboard');
    }

    $pendingRequests = TireRequest::whereHas('user', function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%");
        })
        ->where('status', Approval::STATUS_PENDING)
        ->with(['user', 'vehicle', 'tire'])
        ->get();

    return view('dashboard.section_manager.section_manager', compact('pendingRequests'));
}

    /** ---------------- DRIVERS ---------------- */
    public function drivers(Request $request)
    {
        $query = Driver::with('user');

        if ($search = $request->input('search')) {
            $query->where('full_name', 'like', "%{$search}%");
        }

        $drivers = $query->orderByDesc('id')->get();

        return view('dashboard.section_manager.drivers.index', compact('drivers'));
    }

    public function createDriver()
    {
        return view('admin.drivers.create');
    }

    public function storeDriver(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'full_name' => 'required|string|max:255',
            'mobile' => 'nullable|string|max:20',
            'id_number' => 'nullable|string|max:20',
        ]);

        Driver::create($request->all());

        return redirect()->route('section_manager.drivers.index')
            ->with('success', '✅ Driver added successfully.');
    }

    public function destroyDriver($id)
    {
        Driver::findOrFail($id)->delete();

        return redirect()->route('section_manager.drivers.index')
            ->with('success', '🗑️ Driver deleted successfully.');
    }
}
