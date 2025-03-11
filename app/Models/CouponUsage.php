<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CouponUsage extends Model
{
    protected $fillable = ['user_id', 'coupon_id', 'used_at'];

    // علاقة بالمستخدم
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // علاقة بالكوبون
    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }
}
