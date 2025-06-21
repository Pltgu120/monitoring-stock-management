<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ItemTemplateExport implements WithHeadings, ShouldAutoSize
{
    /**
     * Tentukan heading (header kolom) untuk file Excel template.
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'Part Name',
            'Part Number',
            'Quantity',
            'Kode Rak',
            'Date Items',
            'Person Name',
            'Brand Name',
            'Unit Name',
            'Initial Qty',
        ];
    }
}
