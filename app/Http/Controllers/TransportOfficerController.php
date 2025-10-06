<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TireRequest;
use App\Models\Approval;
use Illuminate\Support\Facades\Auth;

class TransportOfficerController extends Controller
{
    public function __construct()
    {
        // Only Transport Officer access
        $this->middleware(function ($request, $next) {
            $user = auth()->user();
            if (!$user || !$user->role || strtolower($user->role->name) !== 'transport officer') {
                abort(403, 'Access restricted to Transport Officer.');
            }
            return $next($request);
        });
    }

    /** ---------------- PENDING REQUESTS ---------------- */
    public function pending()
    {
        $pendingRequests = TireRequest::where('status', Approval::STATUS_PENDING_TRANSPORT)
            ->where('current_level', Approval::LEVEL_TRANSPORT_OFFICER)
            ->with(['user', 'driver', 'vehicle', 'tire'])
            ->orderByDesc('created_at')
            ->get();

        return view('dashboard.transport_officer.pending', compact('pendingRequests'));
    }

    /** ---------------- APPROVED REQUESTS ---------------- */
    public function approved()
    {
        $approvedRequests = TireRequest::where('status', Approval::STATUS_APPROVED_BY_TRANSPORT)
            ->with(['user', 'driver', 'vehicle', 'tire'])
            ->orderByDesc('updated_at')
            ->get();

        return view('dashboard.transport_officer.approved', compact('approvedRequests'));
    }

    /** ---------------- REJECTED REQUESTS ---------------- */
    public function rejected()
    {
        $rejectedRequests = TireRequest::where('status', Approval::STATUS_REJECTED_BY_TRANSPORT)
            ->with(['user', 'driver', 'vehicle', 'tire'])
            ->orderByDesc('updated_at')
            ->get();

        return view('dashboard.transport_officer.rejected', compact('rejectedRequests'));
    }

    /** ---------------- EDIT REQUEST ---------------- */
    public function edit($id)
    {
        $requestItem = TireRequest::with(['user', 'vehicle', 'tire', 'approvals'])->findOrFail($id);
        return view('dashboard.transport_officer.edit_request', compact('requestItem'));
    }

    /** ---------------- UPDATE REQUEST ---------------- */
    public function update(Request $request, $id)
    {
        $requestItem = TireRequest::findOrFail($id);

        $requestItem->update([
            'damage_description' => $request->damage_description,
        ]);

        $status = match ($request->status) {
            'approved' => Approval::STATUS_APPROVED_BY_TRANSPORT,
            'rejected' => Approval::STATUS_REJECTED_BY_TRANSPORT,
            default => Approval::STATUS_PENDING_TRANSPORT,
        };

        Approval::updateOrCreate(
            ['request_id' => $requestItem->id, 'level' => Approval::LEVEL_TRANSPORT_OFFICER],
            [
                'remarks' => $request->remarks,
                'approved_by' => auth()->id(),
                'status' => $status,
            ]
        );

        if ($status === Approval::STATUS_APPROVED_BY_TRANSPORT) {
            $requestItem->update([
                'status' => Approval::STATUS_APPROVED_BY_TRANSPORT,
                'current_level' => Approval::LEVEL_FINISHED,
            ]);
            return redirect()->route('transport_officer.approved')
                ->with('success', '✅ Request updated and approved.');
        }

        if ($status === Approval::STATUS_REJECTED_BY_TRANSPORT) {
            $requestItem->update([
                'status' => Approval::STATUS_REJECTED_BY_TRANSPORT,
                'current_level' => Approval::LEVEL_TRANSPORT_OFFICER,
            ]);
            return redirect()->route('transport_officer.rejected')
                ->with('error', '❌ Request updated and rejected.');
        }

        // Pending
        $requestItem->update([
            'status' => Approval::STATUS_PENDING_TRANSPORT,
            'current_level' => Approval::LEVEL_TRANSPORT_OFFICER,
        ]);

        return redirect()->route('transport_officer.pending')
            ->with('success', '⏳ Request updated and remains pending.');
    }

    /** ---------------- APPROVE QUICK ACTION ---------------- */
    public function approve($id)
    {
        $req = TireRequest::findOrFail($id);

        $req->update([
            'status' => Approval::STATUS_APPROVED_BY_TRANSPORT,
            'current_level' => Approval::LEVEL_FINISHED,
        ]);

        Approval::updateOrCreate(
            ['request_id' => $req->id, 'level' => Approval::LEVEL_TRANSPORT_OFFICER],
            [
                'approved_by' => Auth::id(),
                'status' => Approval::STATUS_APPROVED_BY_TRANSPORT,
            ]
        );

        return redirect()->route('transport_officer.approved')
            ->with('success', '✅ Request approved successfully.');
    }

    /** ---------------- REJECT QUICK ACTION ---------------- */
    public function reject($id)
    {
        $req = TireRequest::findOrFail($id);

        $req->update([
            'status' => Approval::STATUS_REJECTED_BY_TRANSPORT,
            'current_level' => Approval::LEVEL_TRANSPORT_OFFICER,
        ]);

        Approval::updateOrCreate(
            ['request_id' => $req->id, 'level' => Approval::LEVEL_TRANSPORT_OFFICER],
            [
                'approved_by' => Auth::id(),
                'status' => Approval::STATUS_REJECTED_BY_TRANSPORT,
            ]
        );

        return redirect()->route('transport_officer.rejected')
            ->with('error', '❌ Request rejected.');
    }
}
