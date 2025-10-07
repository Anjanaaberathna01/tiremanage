<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TireRequest;
use App\Models\Approval;
use App\Models\Supplier;
use App\Models\Receipt;
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

    $suppliers = Supplier::all(); // ✅ Add this line

    return view('dashboard.transport_officer.approved', compact('approvedRequests', 'suppliers'));
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
                 ->with('success', 'Receipt sent successfully!');

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

public function createReceipt($id)
{
    $tireRequest = TireRequest::with('user')->findOrFail($id);
    $suppliers = Supplier::all();

    return view('dashboard.transport_officer.create_receipt', compact('tireRequest', 'suppliers'));
}
public function storeReceipt(Request $request)
{
    $validated = $request->validate([
        'request_id' => 'required|exists:requests,id',
        'supplier_id' => 'required|exists:suppliers,id',
        'amount' => 'required|numeric',
        'description' => 'nullable|string',
    ]);

    $tireRequest = TireRequest::findOrFail($validated['request_id']);

    // ✅ Create new receipt record
    Receipt::create([
        'request_id' => $tireRequest->id,
        'user_id' => $tireRequest->user_id, // driver user_id
        'supplier_id' => $validated['supplier_id'],
        'amount' => $validated['amount'],
        'description' => $validated['description'] ?? null,
    ]);

    // ✅ Update status so it stays visible in "Approved Requests"
    $tireRequest->update([
        'status' => Approval::STATUS_APPROVED_BY_TRANSPORT,
        'current_level' => Approval::LEVEL_FINISHED, // stays in finished state
    ]);

    // ✅ Update or create approval record with a “receipt sent” remark
    Approval::updateOrCreate(
        ['request_id' => $tireRequest->id, 'level' => Approval::LEVEL_TRANSPORT_OFFICER],
        [
            'approved_by' => auth()->id(),
            'status' => Approval::STATUS_APPROVED_BY_TRANSPORT,
            'remarks' => 'Receipt sent successfully.',
        ]
    );

    return redirect()->route('transport_officer.approved')
        ->with('success', '✅ Receipt sent successfully and saved in Approved Requests.');
}


public function generateReceipt($user_id, Request $request)
{
    $request = request::findOrFail($user_id);

    // Create a new receipt record
    $receipt = new Receipt();
    $receipt->tire_request_id = $request->id;
    $receipt->user_id = $request->user_id; // link to the driver’s user_id
    $receipt->supplier_id = $request->supplier_id;
    $receipt->issued_date = now();
    $receipt->status = 'issued';
    $receipt->save();

    return redirect()->back()->with('success', 'Receipt generated successfully!');
}

}