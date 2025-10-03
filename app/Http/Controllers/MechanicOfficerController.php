<?php

namespace App\Http\Controllers;

use App\Models\TireRequest;
use App\Models\Approval;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MechanicOfficerController extends Controller
{
    // 🟢 Dashboard: Pending requests for mechanic
    public function index()
    {
        $requests = TireRequest::whereHas('approvals', function ($q) {
            $q->where('level', 'section_manager')
              ->where('status', 'approved');
        })->where(function ($q) {
            $q->whereDoesntHave('approvals', function ($q2) {
                $q2->where('level', 'mechanic_officer');
            })->orWhereHas('approvals', function ($q3) {
                $q3->where('level', 'mechanic_officer')
                   ->where('status', 'pending');
            });
        })->orderBy('created_at', 'desc')->get();

        return view('dashboard.mechanic_officer.mechanic_officer', compact('requests'));
    }

    // 🟢 Approved requests by mechanic
    public function approved()
    {
        $requests = TireRequest::whereHas('approvals', function ($q) {
            $q->where('level', 'mechanic_officer')
              ->where('status', 'approved');
        })->orderBy('created_at', 'desc')->get();

        return view('dashboard.mechanic_officer.approved', compact('requests'));
    }

    // 🟢 Rejected requests by mechanic
    public function rejected()
    {
        $requests = TireRequest::whereHas('approvals', function ($q) {
            $q->where('level', 'mechanic_officer')
              ->where('status', 'rejected');
        })->orderBy('created_at', 'desc')->get();

        return view('dashboard.mechanic_officer.rejected', compact('requests'));
    }

    // 🟢 Approve a request
    public function approve($id)
    {
        $request = TireRequest::findOrFail($id);

        Approval::updateOrCreate(
            ['request_id' => $request->id, 'level' => 'mechanic_officer'],
            ['status' => 'approved', 'approved_by' => Auth::id()]
        );

        $request->update(['status' => 'approved']);

        return redirect()->back()->with('success', 'Request approved by Mechanic Officer.');
    }

    // 🟢 Reject a request
    public function reject($id)
    {
        $request = TireRequest::findOrFail($id);

        Approval::updateOrCreate(
            ['request_id' => $request->id, 'level' => 'mechanic_officer'],
            ['status' => 'rejected', 'approved_by' => Auth::id()]
        );

        $request->update(['status' => 'rejected']);

        return redirect()->back()->with('success', 'Request rejected by Mechanic Officer.');
    }

    // 🟢 Edit request form
    public function edit($id)
    {
        $request = TireRequest::with('approvals')->findOrFail($id);
        return view('dashboard.mechanic_officer.edit_request', compact('request'));
    }

    // 🟢 Update request (allow mechanic to set pending/approved/rejected)
    public function update(Request $request, $id)
    {
        $tireRequest = TireRequest::findOrFail($id);

        // Validate status
        $request->validate([
            'status' => 'required|in:pending,approved,rejected'
        ]);

        // Update tire request status
        $tireRequest->update([
            'status' => $request->input('status'),
        ]);

        // Update or create mechanic approval
        Approval::updateOrCreate(
            ['request_id' => $tireRequest->id, 'level' => 'mechanic_officer'],
            ['status' => $request->input('status'), 'approved_by' => Auth::id()]
        );

        return redirect()->route('mechanic_officer.dashboard')
                         ->with('success', 'Request updated successfully.');
    }
}