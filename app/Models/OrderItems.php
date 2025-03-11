<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItems extends Model
{
    //
    // Eloquent Relationshp
    // One Order has on item or more

    protected $fillable = ['order_id', 'product_image', 'product_name', 'quantity', 'price', 'total', 'created_at', 'updated_at'];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
