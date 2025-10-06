<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TireRequest;
use App\Models\Approval;
use Illuminate\Support\Facades\Auth;

class MechanicOfficerController extends Controller
{
    public function __construct()
    {
        // Restrict access to only Mechanic Officer users
        $this->middleware(function ($request, $next) {
            $user = auth()->user();
            if (!$user || !$user->role || strtolower($user->role->name) !== 'mechanic officer') {
                abort(403, 'Access restricted to Mechanic Officer.');
            }
            return $next($request);
        });
    }

    /** ---------------- PENDING REQUESTS ---------------- */
    public function pending()
    {
        $pendingRequests = TireRequest::where('status', Approval::STATUS_PENDING_MECHANIC)
            ->where('current_level', Approval::LEVEL_MECHANIC_OFFICER)
            ->with(['user', 'driver', 'vehicle', 'tire'])
            ->orderByDesc('created_at')
            ->get();

        return view('dashboard.mechanic_officer.pending', compact('pendingRequests'));
    }

    /** ---------------- APPROVED REQUESTS ---------------- */
    public function approved()
    {
        $approvedRequests = TireRequest::where('status', Approval::STATUS_APPROVED_BY_MECHANIC)
            ->where('current_level', Approval::LEVEL_TRANSPORT_OFFICER) // next phase
            ->with(['user', 'driver', 'vehicle', 'tire'])
            ->orderByDesc('updated_at')
            ->get();

        return view('dashboard.mechanic_officer.approved', compact('approvedRequests'));
    }

    /** ---------------- REJECTED REQUESTS ---------------- */
    public function rejected()
    {
        $rejectedRequests = TireRequest::where('status', Approval::STATUS_REJECTED_BY_MECHANIC)
            ->where('current_level', Approval::LEVEL_MECHANIC_OFFICER)
            ->with(['user', 'driver', 'vehicle', 'tire'])
            ->orderByDesc('updated_at')
            ->get();

        return view('dashboard.mechanic_officer.rejected', compact('rejectedRequests'));
    }

    /** ---------------- EDIT REQUEST ---------------- */
    public function edit($id)
    {
        $requestItem = TireRequest::with(['user', 'vehicle', 'tire', 'approvals'])->findOrFail($id);
        return view('dashboard.mechanic_officer.edit_request', compact('requestItem'));
    }

    /** ---------------- UPDATE REQUEST ---------------- */
public function update(Request $request, $id)
{
    $requestItem = TireRequest::findOrFail($id);

    // Update description
    $requestItem->update([
        'damage_description' => $request->damage_description,
    ]);

    // Determine status
    $status = match ($request->status) {
        'approved' => Approval::STATUS_APPROVED_BY_MECHANIC,
        'rejected' => Approval::STATUS_REJECTED_BY_MECHANIC,
        default => Approval::STATUS_PENDING_MECHANIC,
    };

    // Update approval table
    Approval::updateOrCreate(
        ['request_id' => $requestItem->id, 'level' => Approval::LEVEL_MECHANIC_OFFICER],
        [
            'remarks' => $request->remarks,
            'approved_by' => auth()->id(),
            'status' => $status,
        ]
    );

    // Update tire request status & level
    if ($status === Approval::STATUS_APPROVED_BY_MECHANIC) {
        $requestItem->update([
            'status' => Approval::STATUS_APPROVED_BY_MECHANIC,
            'current_level' => Approval::LEVEL_TRANSPORT_OFFICER,
        ]);

        return redirect()->route('mechanic_officer.approved')
            ->with('success', '✅ Request updated and approved.');
    }

    if ($status === Approval::STATUS_REJECTED_BY_MECHANIC) {
        $requestItem->update([
            'status' => Approval::STATUS_REJECTED_BY_MECHANIC,
            'current_level' => Approval::LEVEL_MECHANIC_OFFICER,
        ]);

        return redirect()->route('mechanic_officer.rejected')
            ->with('error', '❌ Request updated and rejected.');
    }

    // Default fallback (still pending)
    return redirect()->route('mechanic_officer.pending')
        ->with('success', 'Request updated successfully!');
}


    /** ---------------- APPROVE QUICK ACTION ---------------- */
    public function approve($id)
    {
        $req = TireRequest::findOrFail($id);

        $req->update([
            'status' => Approval::STATUS_APPROVED_BY_MECHANIC,
            'current_level' => Approval::LEVEL_TRANSPORT_OFFICER,
        ]);

        Approval::updateOrCreate(
            ['request_id' => $req->id, 'level' => Approval::LEVEL_MECHANIC_OFFICER],
            [
                'approved_by' => Auth::id(),
                'status' => Approval::STATUS_APPROVED_BY_MECHANIC,
            ]
        );

        return redirect()->route('mechanic_officer.approved')
            ->with('success', '✅ Request approved and sent to Transport Officer.');
    }

    /** ---------------- REJECT QUICK ACTION ---------------- */
    public function reject($id)
    {
        $req = TireRequest::findOrFail($id);

        $req->update([
            'status' => Approval::STATUS_REJECTED_BY_MECHANIC,
            'current_level' => Approval::LEVEL_MECHANIC_OFFICER,
        ]);

        Approval::updateOrCreate(
            ['request_id' => $req->id, 'level' => Approval::LEVEL_MECHANIC_OFFICER],
            [
                'approved_by' => Auth::id(),
                'status' => Approval::STATUS_REJECTED_BY_MECHANIC,
            ]
        );

        return redirect()->route('mechanic_officer.rejected')
            ->with('error', '❌ Request rejected.');
    }
}