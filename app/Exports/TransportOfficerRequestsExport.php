<?php

namespace App\Exports;

use App\Models\TireRequest;
use App\Models\Approval;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TransportOfficerRequestsExport implements FromCollection, WithHeadings
{
    protected $status;

    public function __construct($status)
    {
        $this->status = $status;
    }

    public function collection()
    {
        // Base query with eager loading
        $query = TireRequest::with(['user', 'vehicle', 'tire', 'approvals' => function($q){
            $q->where('level', Approval::LEVEL_TRANSPORT_OFFICER);
        }]);

        // Map status string to DB constants
        $statusMap = [
            'pending'  => Approval::STATUS_PENDING_TRANSPORT,
            'approved' => Approval::STATUS_APPROVED_BY_TRANSPORT,
            'rejected' => Approval::STATUS_REJECTED_BY_TRANSPORT,
        ];

        // Apply filter by status
        if ($this->status !== 'all') {
            $mappedStatus = $statusMap[$this->status] ?? null;

            if ($this->status === 'pending') {
                // 🧠 Include requests currently waiting for Transport Officer
                $query->where('current_level', Approval::LEVEL_TRANSPORT_OFFICER)
                      ->where(function ($q) use ($mappedStatus) {
                          $q->where('status', $mappedStatus)
                            ->orWhereDoesntHave('approvals', function ($sub) {
                                $sub->where('level', Approval::LEVEL_TRANSPORT_OFFICER);
                            });
                      });
            } else {
                // Approved or Rejected
                $query->whereHas('approvals', function($q) use ($mappedStatus) {
                    $q->where('level', Approval::LEVEL_TRANSPORT_OFFICER)
                      ->where('status', $mappedStatus);
                });
            }
        }

        // Get results and map for Excel
        return $query->get()->map(function ($request) {
            $approval = $request->approvals->first();
            $statusMap = [
                Approval::STATUS_PENDING_TRANSPORT => 'Pending',
                Approval::STATUS_APPROVED_BY_TRANSPORT => 'Approved',
                Approval::STATUS_REJECTED_BY_TRANSPORT => 'Rejected',
            ];

            return [
                'Request ID'   => $request->id,
                'Driver'       => $request->user->name ?? 'N/A',
                'Vehicle'      => $request->vehicle->plate_no ?? 'N/A',
                'Tire'         => $request->tire->brand ?? 'N/A',
                'Tire Size'    => $request->tire->size ?? 'N/A',
                'Tire Count'   => $request->tire_count,
                'Status'       => $statusMap[$approval->status ?? $request->status] ?? 'Pending',
                'Remarks'      => $approval->remarks ?? '',
                'Branch'       => $request->vehicle->branch ?? 'N/A',
                'Created At'   => $request->created_at->format('Y-m-d'),
                'Updated At'   => $request->updated_at->format('Y-m-d'),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Request ID',
            'Driver',
            'Vehicle',
            'Tire',
            'Tire Size',
            'Tire Count',
            'Status',
            'Remarks',
            'Branch',
            'Created At',
            'Updated At',
        ];
    }
}