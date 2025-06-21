<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoodsOutMechanical extends Model
{
    // table_name = goods_out_mechanical

    protected $table = 'goods_out_mechanical';

    // attribute = item_mechanical_id, user_id, image, quantity, date_out, invoice_number

    protected $fillable = [
        'item_mechanical_id',
        'user_id',
        'image',
        'quantity',
        'date_out',
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
