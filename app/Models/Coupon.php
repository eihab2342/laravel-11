<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'discount_type',
        'fixed_amount',
        'discount_percentage',
        'max_discount_amount',
        'category_id',
        'usage_limit',
        'status',
        'expires_at',
    ];

    public function usages()
    {
        return $this->hasMany(CouponUsage::class);
    }

    // علاقة بالفئات
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
// {success: true, discount: null, newTotal: 700, message: "تم تطبيق الخصم بنجاح"}
// discount
// : 
// null
// message
// : 
// "تم تطبيق الخصم بنجاح"
// newTotal
// : 
// 700
// success
// : 
// true