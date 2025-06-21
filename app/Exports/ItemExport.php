<?php

namespace App\Exports;

use App\Models\Item;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Events\AfterExport;

class ItemExport implements FromCollection, WithHeadings, WithStyles, WithEvents
{
    public function collection()
    {
        return Item::select(
            'part_name',
            'part_number',
            'quantity',
            'kode_rak',
            'unit_name',
            'brand_name',
            'person_name',
            'date_items'
        )->get();
    }

    public function headings(): array
    {
        return [
            'Part Name',
            'Part Number',
            'Quantity',
            'Kode Rak',
            'Unit Name',
            'Brand Name',
            'Person Name',
            'Date Items',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Set header styles
        $sheet->getStyle('A1:H1')->getFont()->setBold(true)->setSize(12)->setColor(new Color(Color::COLOR_WHITE));
        $sheet->getStyle('A1:H1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
        $sheet->getStyle('A1:H1')->getFill()->getStartColor()->setARGB('FF4F81BD'); // Background color
        $sheet->getStyle('A1:H1')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle('A1:H1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Set column widths
        foreach (range('A', 'H') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }
    }

    public function registerEvents(): array
    {
        return [
            AfterExport::class => function(AfterExport $event) {
                \Log::info('AfterExport event triggered.'); // Log untuk memeriksa pemanggilan event
                $sheet = $event->getSheet()->getDelegate();
                $sheet->setCellValue('A1', 'Data Barang');
                $sheet->mergeCells('A1:H1');
                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
                $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            },
        ];
    }
    
}
