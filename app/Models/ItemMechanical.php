<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ItemMechanical extends Model
{
    // table item_mechanical

    use HasFactory;
    protected $table = 'item_mechanical';

    // attribute image, file_reference, quantity, initial_qty
    protected $fillable = [
        'image',
        'file_reference',
        'quantity',
        'initial_qty',
        'part_name',
        'part_number',
        'kode_rak',
        'date_items_mechanical',
        'person_name',
        'unit_name',
        'brand_name',
        'active',
        'status'
    ];

    // relationship dengan GoodsInMechanical
    public function goodsInMechanicals(): HasMany
    {
        return $this->hasMany(GoodsInMechanical::class);
    }

    // relationship dengan GoodsOutMechanical

    public function goodsOutMechanicals(): HasMany
    {
        return $this->hasMany(GoodsOutMechanical::class);
    }
}
