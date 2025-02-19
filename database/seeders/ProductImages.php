<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductImages extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('product_images')->insert([
            ['product_id' => 295, 'image' => 'https://images.unsplash.com/photo-1629131726692-1accd0c53ce0?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8OHx8bGFwdG9wc3xlbnwwfHwwfHx8MA%3D%3D'],
            ['product_id' => 295, 'image' => 'https://images.unsplash.com/photo-1588872657578-7efd1f1555ed?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8NHx8bGFwdG9wc3xlbnwwfHwwfHx8MA%3D%3D'],
            ['product_id' => 295, 'image' => 'https://images.unsplash.com/photo-1593642702821-c8da6771f0c6?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8M3x8bGFwdG9wc3xlbnwwfHwwfHx8MA%3D%3D'],

            ['product_id' => 296, 'image' => 'https://images.unsplash.com/photo-1525598912003-663126343e1f?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8cGhvbmV8ZW58MHx8MHx8fDA%3D'],
            ['product_id' => 296, 'image' => 'https://images.unsplash.com/photo-1523206489230-c012c64b2b48?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8N3x8cGhvbmV8ZW58MHx8MHx8fDA%3D'],

            // ['product_id' => 297, 'image' => '1418 حمام كريم جلامور.png'],
            // ['product_id' => 297, 'image' => '1418 حمام كريم جلامور.png'],
            // ['product_id' => 297, 'image' => '1418 حمام كريم جلامور.png'],
            // // ------------
            // ['product_id' => 298, 'image' => '1556 شامبو وبلسم جلامور.png'],
            // ['product_id' => 298, 'image' => '1556 شامبو وبلسم جلامور.png'],
            // ['product_id' => 298, 'image' => '1556 شامبو وبلسم جلامور.png'],
            // // ------------
            // ['product_id' => 299, 'image' => '1557 شامبو وبلسم جلامور.png'],
            // ['product_id' => 299, 'image' => '1557 شامبو وبلسم جلامور.png'],
            // ['product_id' => 299, 'image' => '1557 شامبو وبلسم جلامور.png'],
            // // ------------
            // ['product_id' => 300, 'image' => '1558 شامبو وبلسم جلامور.png'],
            // ['product_id' => 300, 'image' => '1558 شامبو وبلسم جلامور.png'],
            // ['product_id' => 300, 'image' => '1558 شامبو وبلسم جلامور.png'],
        ]);
    }
}
