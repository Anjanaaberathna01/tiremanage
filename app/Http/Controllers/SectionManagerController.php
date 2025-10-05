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

    /** ---------------- DASHBOARD (Pending Requests) ---------------- */
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

    /** ---------------- APPROVED REQUESTS ---------------- */
public function approved()
{
    // Get approvals made by Section Manager
    $approvedRecords = Approval::where('level', Approval::LEVEL_SECTION_MANAGER)
        ->where('status', Approval::STATUS_APPROVED)
        ->with(['request.user', 'request.vehicle', 'request.tire'])
        ->orderByDesc('updated_at')
        ->get();

    return view('dashboard.section_manager.approved', compact('approvedRecords'));
}


    /** ---------------- REJECTED REQUESTS ---------------- */
    public function rejected()
    {
        $requests = TireRequest::where('status', Approval::STATUS_REJECTED)
            ->with(['user', 'vehicle', 'tire'])
            ->orderByDesc('updated_at')
            ->get();

        return view('dashboard.section_manager.rejected', compact('requests'));
    }

    /** ---------------- APPROVE REQUEST ---------------- */
    public function approve($id)
    {
        $requestItem = TireRequest::findOrFail($id);

        // ✅ Step 1: Update TireRequest → forward to Mechanic Officer
        $requestItem->update([
            'status' => Approval::STATUS_PENDING_MECHANIC, // waiting for Mechanic Officer
            'current_level' => Approval::LEVEL_MECHANIC_OFFICER,
        ]);

        // ✅ Step 2: Log in Approval table
        Approval::updateOrCreate(
            ['request_id' => $requestItem->id, 'level' => Approval::LEVEL_SECTION_MANAGER],
            [
                'approved_by' => auth()->id(),
                'status' => Approval::STATUS_APPROVED,
            ]
        );

        // ✅ Step 3: Redirect → approved list
        return redirect()->route('section_manager.requests.approved_list')
            ->with('success', '✅ Request approved successfully and forwarded to Mechanic Officer.');
    }

    /** ---------------- REJECT REQUEST ---------------- */
    public function reject($id)
    {
        $requestItem = TireRequest::findOrFail($id);

        // ✅ Step 1: Update request
        $requestItem->update([
            'status' => Approval::STATUS_REJECTED,
            'current_level' => Approval::LEVEL_SECTION_MANAGER,
        ]);

        // ✅ Step 2: Log in Approval table
        Approval::updateOrCreate(
            ['request_id' => $requestItem->id, 'level' => Approval::LEVEL_SECTION_MANAGER],
            [
                'approved_by' => auth()->id(),
                'status' => Approval::STATUS_REJECTED,
            ]
        );

        // ✅ Step 3: Redirect → rejected list
        return redirect()->route('section_manager.requests.rejected_list')
            ->with('error', '❌ Request rejected successfully.');
    }

    /** ---------------- EDIT REQUEST ---------------- */
    public function edit($id)
    {
        $requestItem = TireRequest::with(['user', 'vehicle', 'tire', 'approvals'])->findOrFail($id);
        return view('dashboard.section_manager.edit_request', compact('requestItem'));
    }

    /** ---------------- UPDATE REQUEST ---------------- */
    public function update(Request $req, $id)
    {
        $requestItem = TireRequest::findOrFail($id);

        // ✅ Update description and remarks
        $requestItem->update([
            'damage_description' => $req->damage_description,
            'current_level' => Approval::LEVEL_SECTION_MANAGER,
        ]);

        if ($req->filled('status')) {
            $requestItem->update(['status' => $req->status]);

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
        }

        // ✅ Redirect based on status
        if ($req->status === 'approved') {
            return redirect()->route('section_manager.requests.approved_list')
                ->with('success', '✅ Request updated and moved to approved list.');
        } elseif ($req->status === 'rejected') {
            return redirect()->route('section_manager.requests.rejected_list')
                ->with('error', '❌ Request updated and moved to rejected list.');
        }

        return redirect()->route('section_manager.requests.pending')
            ->with('success', '✏️ Request updated successfully.');
    }

    /** ---------------- SEARCH ---------------- */
    public function search(Request $request)
    {
        $search = trim($request->input('search'));

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

    /** ---------------- DRIVER MANAGEMENT ---------------- */
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
