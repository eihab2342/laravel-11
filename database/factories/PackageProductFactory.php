<?php

namespace Database\Factories;

use App\Models\Package;
use App\Models\Product;
use App\Models\PackageProduct;
use Illuminate\Database\Eloquent\Factories\Factory;

class PackageProductFactory extends Factory
{
    protected $model = PackageProduct::class;

    public function definition()
    {
        return [
            'package_id' => Package::inRandomOrder()->first()->id ?? Package::factory(),
            'product_id' => Product::inRandomOrder()->first()->id ?? Product::factory(),
        ];
    }
}
