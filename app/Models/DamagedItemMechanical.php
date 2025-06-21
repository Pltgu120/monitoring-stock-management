<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DamagedItemMechanical extends Model
{
    // table = damaged_items_mechanical

    protected $table = 'damaged_items_mechanical';

    protected $fillable = [
        'image',
        'quantity',
        'initial_qty',
        'part_name',
        'part_number',
        'kode_rak',
        'date_damaged_items',
        'person_name',
        'unit_name',
        'brand_name',
        'status'
    ];
}
