<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'price', 'status'];

    public function images()
    {
        return $this->hasMany(PackageImages::class, 'package_id');
    }
    // العلاقة بين الباكدج والعربة
    public function carts()
    {
        return $this->hasMany(Cart::class, 'package_id');
    }
}
