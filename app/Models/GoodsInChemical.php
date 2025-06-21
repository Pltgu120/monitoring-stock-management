<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoodsInChemical extends Model
{
    // table_name = goods_in_chemical

    protected $table = 'goods_in_chemical';

    // attribute = item_chemical_id, user_id, image, quantity, date_received_chemical, invoice_number

    protected $fillable = [
        'item_chemical_id',
        'user_id',
        'image',
        'quantity',
        'date_received_chemical',
        'invoice_number'
    ];

    public function itemChemical()
    {
        return $this->belongsTo(ItemChemical::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
}
