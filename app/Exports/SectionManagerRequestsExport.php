<?php

namespace App\Exports;

use App\Models\TireRequest;
use App\Models\Approval;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SectionManagerRequestsExport implements FromCollection, WithHeadings
{
    protected $status;

    public function __construct($status)
    {
        $this->status = $status;
    }

    public function collection()
    {
        // Only section manager level approvals
        $query = TireRequest::with(['user', 'vehicle', 'tire', 'approvals' => function($q){
            $q->where('level', Approval::LEVEL_SECTION_MANAGER);
        }]);

        if ($this->status !== 'all') {
            $query->whereHas('approvals', function($q){
                $q->where('level', Approval::LEVEL_SECTION_MANAGER)
                  ->where('status', $this->status);
            });
        }

        return $query->get()->map(function($request){
            $approval = $request->approvals->first(); // Section Manager approval
            return [
                'Request ID' => $request->id,
                'Driver' => $request->user->name ?? 'N/A',
                'Vehicle' => $request->vehicle->plate_no ?? 'N/A',
                'Tire' => $request->tire->brand ?? 'N/A',
                'Tire Size' => $request->tire->size ?? 'N/A',
                'Tire Count' => $request->tire_count,
                'Status' => ucfirst($approval->status ?? $request->status),
                'Remarks' => $approval->remarks ?? '',
                'Branch' => $request->vehicle->branch ?? 'N/A',
                'Created At' => $request->created_at->format('Y-m-d'),
                'Updated At' => $request->updated_at->format('Y-m-d'),
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