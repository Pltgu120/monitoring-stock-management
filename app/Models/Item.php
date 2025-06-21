<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Item extends Model
{
    use HasFactory;
    protected $table = 'items';
    protected $fillable = [
        'image','quantity','part_name', 'part_number', 'kode_rak', 'date_items', 'person_name', 'file_reference',
        'active', 'unit_name', 'brand_name', 'initial_qty', 'status'
    ];

    public function goodsIns():HasMany
    {
        return $this -> hasMany(GoodsIn::class);
    }

    public function goodsOuts():HasMany
    {
        return $this -> hasMany(GoodsOut::class);
    }

}
