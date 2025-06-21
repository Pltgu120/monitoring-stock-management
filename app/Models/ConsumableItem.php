<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConsumableItem extends Model
{
    //table = consumable_items

    protected $table = 'consumable_items';

    protected $fillable = [
        'image',
        'quantity',
        'initial_qty',
        'part_name',
        'part_number',
        'kode_rak',
        'date_consumable_items',
        'person_name',
        'unit_name',
        'brand_name',
        'status'
    ];
}
