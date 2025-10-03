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
        // Ensure only authenticated users with mechanic_officer role can access
        $this->middleware(function ($request, $next) {
            $user = Auth::user();
            if (!$user || !$user->role) {
                abort(403, 'Unauthorized.');
            }

            $role = strtolower(str_replace([' ', '-'], '_', trim($user->role->name)));
            if ($role !== 'mechanic_officer') {
                abort(403, 'Access restricted to Mechanic Officer.');
            }

            return $next($request);
        });
    }
    // Show requests that have been approved by section manager (status = 'approved')
    public function index()
    {
        $requests = TireRequest::with(['user', 'vehicle', 'tire'])
            ->where('status', 'approved')
            ->orderByDesc('updated_at')
            ->get();

        return view('dashboard.mechanic_officer.mechanic_officer', compact('requests'));
    }

    // Show all requests that are approved (for mechanic view - approved list)
    public function approved()
    {
        $requests = TireRequest::with(['user', 'vehicle', 'tire'])
            ->where('status', 'approved')
            ->orderByDesc('updated_at')
            ->get();

        return view('dashboard.mechanic_officer.approved', ['requests' => $requests]);
    }

    // Show all requests that are rejected
    public function rejected()
    {
        $requests = TireRequest::with(['user', 'vehicle', 'tire'])
            ->where('status', 'rejected')
            ->orderByDesc('updated_at')
            ->get();

        return view('dashboard.mechanic_officer.rejected', ['requests' => $requests]);
    }

    // Mechanic approves the request (final approval)
    public function approve($id)
    {
        $req = TireRequest::findOrFail($id);

        // Only act on manager-approved requests
        if ($req->status !== 'approved') {
            return redirect()->back()->with('error', 'Only manager-approved requests can be processed by mechanic.');
        }

        // Record mechanic approval
        Approval::create([
            'request_id' => $req->id,
            'approved_by' => Auth::id(),
            'level' => 'mechanic_officer',
            'status' => 'approved',
            'remarks' => null,
        ]);

        // Optionally keep request status as 'approved' or set a separate final flag.
        // We'll keep it as 'approved' to indicate it's cleared for transport.
        $req->status = 'approved';
        $req->save();

        return redirect()->back()->with('success', 'Request approved by mechanic.');
    }

    // Mechanic rejects the request
    public function reject($id)
    {
        $req = TireRequest::findOrFail($id);

        if ($req->status !== 'approved') {
            return redirect()->back()->with('error', 'Only manager-approved requests can be processed by mechanic.');
        }

        Approval::create([
            'request_id' => $req->id,
            'approved_by' => Auth::id(),
            'level' => 'mechanic_officer',
            'status' => 'rejected',
            'remarks' => null,
        ]);

        // Mark request as rejected
        $req->status = 'rejected';
        $req->save();

        return redirect()->back()->with('success', 'Request rejected by mechanic.');
    }
}
