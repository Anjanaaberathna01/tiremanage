<?php

namespace App\Http\Controllers;

use App\Models\TireRequest;
use App\Models\Approval;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApprovalController extends Controller
{
    public function handle(Request $request, $id)
    {
        $user = $request->user();
        $tireRequest = TireRequest::findOrFail($id);

        // Detect user role level
        if ($user->isSectionManager()) {
            $level = Approval::LEVEL_SECTION_MANAGER;
        } elseif ($user->isMechanicOfficer()) {
            $level = Approval::LEVEL_MECHANIC_OFFICER;
        } elseif ($user->hasRole('transport_officer')) {
            $level = Approval::LEVEL_TRANSPORT_OFFICER;
        } else {
            abort(403, 'Unauthorized role.');
        }

        // Ensure it's their current phase
        if ($tireRequest->current_level != $level) {
            abort(403, 'This request is not in your approval phase.');
        }

        $data = $request->validate([
            'action' => 'required|in:approved,rejected,pending',
            'remarks' => 'nullable|string|max:2000',
        ]);

        DB::transaction(function () use ($tireRequest, $user, $level, $data) {
            Approval::updateOrCreate(
                ['request_id' => $tireRequest->id, 'level' => $level],
                [
                    'approved_by' => $user->id,
                    'status' => $data['action'],
                    'remarks' => $data['remarks'] ?? null,
                ]
            );

            // Transition Logic
            if ($data['action'] === Approval::STATUS_APPROVED) {
                if ($level === Approval::LEVEL_SECTION_MANAGER) {
                    $tireRequest->moveToMechanicPhase();
                } elseif ($level === Approval::LEVEL_MECHANIC_OFFICER) {
                    $tireRequest->moveToTransportPhase();
                } elseif ($level === Approval::LEVEL_TRANSPORT_OFFICER) {
                    $tireRequest->finishWorkflow(Approval::STATUS_APPROVED);
                }
            } elseif ($data['action'] === Approval::STATUS_REJECTED) {
                $tireRequest->finishWorkflow(Approval::STATUS_REJECTED);
            } else {
                $tireRequest->update(['status' => Approval::STATUS_PENDING]);
            }
        });

        return back()->with('success', 'Action recorded successfully.');
    }
}