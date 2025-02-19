<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'email',
        'phone_number',
        'governorate',
        'city',
        'village',
        'shipping_address',
        'payment_status',
        'total_amount',
    ];


    // Eloquent Relationshp
    // One Order has on item or more
    public function items()
    {
        return $this->hasMany(OrderItems::class, 'order_id');
    }
}
