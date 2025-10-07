<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TireRequest;
use App\Models\Approval;
use App\Models\Supplier;
use App\Models\Receipt;
use Illuminate\Validation\Rule;
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
    // load request with relations
    $tireRequest = TireRequest::with(['user', 'driver', 'vehicle', 'tire'])->findOrFail($id);
    $suppliers = Supplier::all();

    // If you already open the inline form in approved.blade.php you can keep this view or ignore it.
    return view('dashboard.transport_officer.create_receipt', compact('tireRequest', 'suppliers'));
}

public function storeReceipt(Request $request)
{
    $tireRequestModel = new TireRequest();
    $supplierModel = new Supplier();

    $validated = $request->validate([
        'request_id'  => ['required', Rule::exists($tireRequestModel->getTable(), 'id')],
        'supplier_id' => ['required', Rule::exists($supplierModel->getTable(), 'id')],
        'amount'      => 'required|numeric',
        'description' => 'nullable|string',
    ]);

    $tireRequest = TireRequest::with(['user', 'vehicle'])->findOrFail($validated['request_id']);
    $supplier = Supplier::findOrFail($validated['supplier_id']);

    // ✅ Create receipt record
    $receipt = Receipt::create([
        'request_id'  => $tireRequest->id,
        'user_id'     => $tireRequest->user_id,
        'supplier_id' => $supplier->id,
        'amount'      => $validated['amount'],
        'description' => $validated['description'] ?? null,
    ]);

    try {
        $receipt->issued_date = now();
        $receipt->status = 'issued';
        $receipt->save();
    } catch (\Throwable $e) {
        // ignore if column doesn't exist
    }

    // ✅ Update tire request + approval table
    $tireRequest->update([
        'status' => Approval::STATUS_APPROVED_BY_TRANSPORT,
        'current_level' => Approval::LEVEL_FINISHED,
    ]);

    Approval::updateOrCreate(
        ['request_id' => $tireRequest->id, 'level' => Approval::LEVEL_TRANSPORT_OFFICER],
        [
            'approved_by' => auth()->id(),
            'status' => Approval::STATUS_APPROVED_BY_TRANSPORT,
            'remarks' => 'Receipt sent to supplier ' . $supplier->name,
        ]
    );

    // ✅ Prepare WhatsApp message (supplier only)
    if (empty($supplier->contact)) {
        return redirect()->route('transport_officer.approved')
            ->with('error', '⚠️ Supplier contact number not found. WhatsApp message not sent.');
    }

    $driverName   = $tireRequest->user->name ?? 'N/A';
    $vehiclePlate = $tireRequest->vehicle->plate_no ?? 'N/A';

    $messageLines = [
        "📦 Tire Request Receipt",
        "------------------------------",
        "Request ID: {$tireRequest->id}",
        "Driver: {$driverName}",
        "Vehicle: {$vehiclePlate}",
        "Amount: " . number_format($receipt->amount, 2),
    ];

    if (!empty($receipt->description)) {
        $messageLines[] = "Description: {$receipt->description}";
    }

    $messageLines[] = "Issued on: " . now()->toDateString();
    $message = implode("\n", $messageLines);

    // ✅ Normalize contact and open WhatsApp
    $phoneDigits = $this->normalizePhoneForWhatsApp($supplier->contact);
    $waLink = "https://wa.me/{$phoneDigits}?text=" . urlencode($message);

    // ✅ Redirect directly to WhatsApp
    return redirect()->away($waLink);
}


public function generateReceiptForDriver($requestId)
{
    $tireRequest = TireRequest::with(['user','vehicle'])->findOrFail($requestId);
    $receipt = Receipt::create([
        'request_id' => $tireRequest->id,
        'user_id'    => $tireRequest->user_id,
        'supplier_id'=> null,
        'amount'     => 0,
        'description'=> 'Generated for driver',
    ]);
    $receipt->issued_date = now();
    $receipt->status = 'issued';
    $receipt->save();

    return redirect()->back()->with('success', 'Receipt generated for driver.');
}

/**
 * Normalize supplier contact to international digits for wa.me links (no + sign).
 * Examples:
 *   "+94711234567" -> "94711234567"
 *   "0711234567"   -> "94711234567" (uses DEFAULT_PHONE_COUNTRY)
 */
private function normalizePhoneForWhatsApp(string $rawPhone): string
{
    // remove everything except digits and plus
    $phone = preg_replace('/[^0-9+]/', '', (string)$rawPhone);

    $defaultCountry = config('app.default_phone_country', env('DEFAULT_PHONE_COUNTRY', '94'));

    if (strpos($phone, '+') === 0) {
        return ltrim($phone, '+');
    }

    // remove leading zero(s)
    $phone = preg_replace('/^0+/', '', $phone);

    // If phone looks short (no country code) prepend default country code
    if (strlen($phone) <= 9) {
        $phone = $defaultCountry . $phone;
    }

    return $phone;
}


}
