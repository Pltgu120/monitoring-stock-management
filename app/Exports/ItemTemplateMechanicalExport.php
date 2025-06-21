<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\FromCollection;

class ItemTemplateMechanicalExport implements WithHeadings, ShouldAutoSize, FromCollection
{
    public function collection()
    {
        // Returning an empty collection since we only need headings
        return collect([]);
    }

    public function headings(): array
    {
        return [
            'Part Name',
            'Part Number',
            'Quantity',
            'Kode Rak',
            'Date Items Mechanical',
            'Person Name',
            'Brand Name',
            'Unit Name',
            'Initial Qty',
        ];
    }
}
