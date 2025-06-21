<?php
namespace App\Imports;

use App\Models\Item;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class ItemImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $dateItems = null;

        if (isset($row['date_items']) && !empty($row['date_items'])) {
            if (is_numeric($row['date_items'])) {
                // Format serial Excel ke Y-m-d
                $dateItems = Date::excelToDateTimeObject($row['date_items'])->format('Y-m-d');
            } else {
                // Konversi dari DD/MM/YYYY ke Y-m-d
                $date = \DateTime::createFromFormat('d/m/Y', $row['date_items']);
                $dateItems = $date ? $date->format('Y-m-d') : null;
            }
        }

        if (!$dateItems) {
            Log::warning('Format date_items tidak valid.', ['date_items' => $row['date_items']]);
            return null; // Abaikan baris ini jika format salah
        }

        return new Item([
            'part_name'      => $row['part_name'] ?? null,
            'part_number'    => $row['part_number'] ?? null,
            'quantity'       => $row['quantity'] ?? null,
            'kode_rak'       => $row['kode_rak'] ?? null,
            'date_items'     => $dateItems,
            'person_name'    => $row['person_name'] ?? null,
            'brand_name'     => $row['brand_name'] ?? null,
            'unit_name'      => $row['unit_name'] ?? null,
            'initial_qty'    => $row['initial_qty'] ?? null,
            'active'         => 'true',
            'status'         => '0',
        ]);
    }
}
