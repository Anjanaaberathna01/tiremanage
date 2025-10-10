<?php

namespace App\Exports;

use App\Models\Driver;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DriversExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Driver::with('user')->get()->map(function ($driver) {
            return [
                'ID' => $driver->id,
                'Username' => $driver->user->name ?? '',
                'Email' => $driver->user->email ?? '',
                'Full Name' => $driver->full_name,
                'Mobile' => $driver->mobile,
                'ID Number' => $driver->id_number,
            ];
        });
    }

    public function headings(): array
    {
        return ['ID', 'Username', 'Email', 'Full Name', 'Mobile', 'ID Number'];
    }
}