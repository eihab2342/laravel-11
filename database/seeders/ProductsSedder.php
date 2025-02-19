<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class ProductsSedder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('products')->insert(
            [
                'name' => 'Product 5',
                'description' => 'Description for Product 5',
                'price' => 500,
                'old_price' => 520,
                'discount' => 20,
                'category' => 'Category 5',
                'rating' => 4.5,
                'keywords' => 'keyword1, keyword2',
                'product_position' => 5,
                'created_at' => now(),
                'updated_at' => now(),
                'position' => 5,
            ]
        );
    }
}
