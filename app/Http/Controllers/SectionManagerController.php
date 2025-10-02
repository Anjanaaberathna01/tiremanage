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
}
