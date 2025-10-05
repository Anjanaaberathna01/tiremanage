<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TireRequest;
use App\Models\Approval;

class MechanicOfficerController extends Controller
{
    public function __construct()
    {
        // ✅ Restrict access to only Mechanic Officer users
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
        // ✅ Fetch requests forwarded from Section Manager
        $pendingRequests = TireRequest::where('status', Approval::STATUS_PENDING_MECHANIC)
            ->where('current_level', Approval::LEVEL_MECHANIC_OFFICER)
            ->with(['user', 'driver', 'vehicle', 'tire'])
            ->orderByDesc('created_at')
            ->get();

        return view('dashboard.mechanic_officer.pending', compact('pendingRequests'));
    }

    /** ---------------- APPROVE REQUEST ---------------- */
    public function approve($id)
    {
        $req = TireRequest::findOrFail($id);

        // ✅ Update status to approved by Mechanic Officer and forward to Transport Officer
        $req->update([
            'status' => Approval::STATUS_APPROVED_BY_MECHANIC, // ✅ standardized constant
            'current_level' => Approval::LEVEL_TRANSPORT_OFFICER, // ✅ move to next level
        ]);

        // ✅ Record approval in Approval table
        Approval::updateOrCreate(
            ['request_id' => $req->id, 'level' => Approval::LEVEL_MECHANIC_OFFICER],
            [
                'approved_by' => auth()->id(),
                'status' => Approval::STATUS_APPROVED,
            ]
        );

        return redirect()
            ->route('mechanic_officer.pending')
            ->with('success', '✅ Request approved and forwarded to Transport Officer successfully.');
    }

    /** ---------------- REJECT REQUEST ---------------- */
    public function reject($id)
    {
        $req = TireRequest::findOrFail($id);

        // ✅ Update status to rejected by Mechanic Officer
        $req->update([
            'status' => Approval::STATUS_REJECTED,
            'current_level' => Approval::LEVEL_MECHANIC_OFFICER, // stays the same level
        ]);

        // ✅ Record rejection in Approval table
        Approval::updateOrCreate(
            ['request_id' => $req->id, 'level' => Approval::LEVEL_MECHANIC_OFFICER],
            [
                'approved_by' => auth()->id(),
                'status' => Approval::STATUS_REJECTED,
            ]
        );

        return redirect()
            ->route('mechanic_officer.pending')
            ->with('error', '❌ Request rejected by Mechanic Officer.');
    }
}
