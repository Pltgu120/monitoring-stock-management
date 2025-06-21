<?php

namespace App\Imports;

use App\Models\ItemChemical; 
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class ItemChemicalImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Log the keys (column headers) available in the row
        Log::info('Heading row:', array_keys($row));  // Check this in your logs
    
        $dateItems = null;
    
        // Ensure 'date_items_chemical' exists in the row
        if (isset($row['date_items_chemical']) && !empty($row['date_items_chemical'])) {
            if (is_numeric($row['date_items_chemical'])) {
                // Format serial Excel date to Y-m-d
                $dateItems = Date::excelToDateTimeObject($row['date_items_chemical'])->format('Y-m-d');
            } else {
                // Convert from DD/MM/YYYY to Y-m-d
                $date = \DateTime::createFromFormat('d/m/Y', $row['date_items_chemical']);
                $dateItems = $date ? $date->format('Y-m-d') : null;
            }
        }
    
        if (!$dateItems) {
            Log::warning('Invalid date_items_chemical format.', ['date_items_chemical' => $row['date_items_chemical']]);
            return null; // Skip this row if the format is invalid
        }
    
        return new ItemChemical([
            'part_name'      => $row['part_name'] ?? null,
            'part_number'    => $row['part_number'] ?? null,
            'quantity'       => $row['quantity'] ?? null,
            'kode_rak'       => $row['kode_rak'] ?? null,
            'date_items_chemical' => $dateItems,
            'person_name'    => $row['person_name'] ?? null,
            'brand_name'     => $row['brand_name'] ?? null,
            'unit_name'      => $row['unit_name'] ?? null,
            'initial_qty'    => $row['initial_qty'] ?? null,
            'active'         => 'true',
            'status'         => '0',
        ]);
    }
}
