<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemChemical extends Model
{
    // table_name = item_chemical

    protected $table = 'item_chemical';

    // attribute = image, file_reference, quantity, initial_qty

    protected $fillable = [
        'image',
        'file_reference',
        'quantity',
        'initial_qty',
        'part_name',
        'part_number',
        'kode_rak',
        'date_items_chemical',
        'person_name',
        'unit_name',
        'brand_name',
        'active',
        'status'
    ];

    // relationship dengan GoodsInChemical

    public function goodsInChemicals()
    {
        return $this->hasMany(GoodsInChemical::class);
    }

    // relationship dengan GoodsOutChemical

    public function goodsOutChemicals()
    {
        return $this->hasMany(GoodsOutChemical::class);
    }
}
