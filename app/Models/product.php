<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class product extends Model
{
    //
    // protected $guarded = ['id'];
    protected $fillable = [
        'name',
        'description',
        'price',
        'old_price',
        'discount',
        'rating',
        'category',
        'keywords',
        'product_position',
    ];
    // protected $fillable = ['name', 'description', 'price', 'quantity'];

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }
    // كل منتج ينتمي إلى فئة واحدة (Many To One)
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    //
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($product) {
            // حذف الصور المرتبطة من مجلد التخزين
            foreach ($product->images as $image) {
                Storage::delete('public/products/' . $image->image);
            }

            // حذف الصور المرتبطة من قاعدة البيانات
            $product->images()->delete();
        });
    }
}
