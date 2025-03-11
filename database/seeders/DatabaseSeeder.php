<?php

namespace Database\Seeders;

use App\Models\Package;
use App\Models\Product;
use App\Models\PackageProduct;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // إنشاء 10 منتجات (لو مش موجودة)
        if (Product::count() == 0) {
            Product::factory(10)->create();
        }

        // إنشاء 5 باقات
        Package::factory(5)->create()->each(function ($package) {
            // ربط كل باقة بمنتجات عشوائية
            $products = Product::inRandomOrder()->limit(rand(2, 5))->pluck('id');
            foreach ($products as $product) {
                PackageProduct::factory()->create([
                    'package_id' => $package->id,
                    'product_id' => $product,
                ]);
            }
        });
    }
}
