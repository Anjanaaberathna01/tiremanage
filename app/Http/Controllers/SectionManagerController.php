<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\TireRequest;

class SectionManagerController extends Controller
{
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
        return redirect()->back()->with('success', 'Request approved.');
    }

    // Reject action
    public function reject($id)
    {
        $req = TireRequest::findOrFail($id);
        $req->status = 'rejected';
        $req->save();
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
}