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

class ConsumableItemExport implements FromCollection, WithHeadings, WithStyles, WithEvents
{
    /**
     * Return the collection of items to export.
     *
     * @return \Illuminate\Support\Collection
     */
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

    /**
     * Define the headings for the Excel sheet.
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
            'Unit Name',
            'Brand Name',
            'Update By',
            'Date Consumable Items',
        ];
    }

    /**
     * Apply styles to the worksheet.
     *
     * @param Worksheet $sheet
     * @return void
     */
    public function styles(Worksheet $sheet)
    {
        // Set header styles
        $sheet->getStyle('A1:H1')->getFont()->setBold(true)->setSize(12)->setColor(new Color(Color::COLOR_WHITE));
        $sheet->getStyle('A1:H1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
        $sheet->getStyle('A1:H1')->getFill()->getStartColor()->setARGB('FF4F81BD'); // Background color

        // Set border for the header
        $sheet->getStyle('A1:H1')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        
        // Set alignment for the header
        $sheet->getStyle('A1:H1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Set column widths
        foreach (range('A', 'H') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }
    }

    /**
     * Register events.
     *
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterExport::class => function(AfterExport $event) {
                // Actions after export can be added here if needed
            },
        ];
    }
}
