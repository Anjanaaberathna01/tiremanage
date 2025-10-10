<?php

namespace App\Exports;

use App\Models\TireRequest;
use App\Models\Approval;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MechanicOfficerRequestsExport implements FromCollection, WithHeadings
{
    protected $status;

    public function __construct($status)
    {
        $this->status = $status;
    }

    public function collection()
{
    $query = TireRequest::with(['user', 'vehicle', 'tire', 'approvals' => function($q){
        $q->where('level', Approval::LEVEL_MECHANIC_OFFICER);
    }]);

    if ($this->status !== 'all') {
        // Map generic status to actual approval status
        $statusMap = [
            'pending'  => Approval::STATUS_PENDING_MECHANIC,
            'approved' => Approval::STATUS_APPROVED_BY_MECHANIC,
            'rejected' => Approval::STATUS_REJECTED_BY_MECHANIC,
        ];

        $mappedStatus = $statusMap[$this->status] ?? null;

        $query->whereHas('approvals', function($q) use ($mappedStatus) {
            $q->where('level', Approval::LEVEL_MECHANIC_OFFICER)
              ->where('status', $mappedStatus);
        });
    }

    return $query->get()->map(function ($request) {
        $approval = $request->approvals->first();
        $statusMap = [
            Approval::STATUS_PENDING_MECHANIC => 'Pending',
            Approval::STATUS_APPROVED_BY_MECHANIC => 'Approved',
            Approval::STATUS_REJECTED_BY_MECHANIC => 'Rejected',
        ];
        return [
            'Request ID'   => $request->id,
            'Driver'       => $request->user->name ?? 'N/A',
            'Vehicle'      => $request->vehicle->plate_no ?? 'N/A',
            'Tire'         => $request->tire->brand ?? 'N/A',
            'Tire Size'    => $request->tire->size ?? 'N/A',
            'Tire Count'   => $request->tire_count,
            'Status'       => $statusMap[$approval->status ?? $request->status] ?? 'N/A',
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