<?php

namespace App\Exports;

use App\Models\Tire;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TiresExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Tire::with('supplier')->get()->map(function ($tire) {
            return [
                'ID' => $tire->id,
                'Brand' => $tire->brand,
                'Size' => $tire->size,
                'Supplier' => $tire->supplier->name ?? 'N/A',
            ];
        });
    }

    public function headings(): array
    {
        return ['ID', 'Brand', 'Size', 'Supplier'];
    }
}