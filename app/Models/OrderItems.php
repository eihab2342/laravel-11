<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItems extends Model
{
    //
    // Eloquent Relationshp
    // One Order has on item or more
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

}
