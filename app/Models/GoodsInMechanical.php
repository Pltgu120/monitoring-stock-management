<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoodsInMechanical extends Model
{
    //table name = goods_in_mechanical

    protected $table = 'goods_in_mechanical';
    protected $fillable = [
        'item_mechanical_id',
        'user_id',
        'image',
        'quantity',
        'date_received_mechanical',
        'invoice_number'
    ];

    public function itemMechanical()
    {
        return $this->belongsTo(ItemMechanical::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
