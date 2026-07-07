<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            // Makanan
            ['name' => 'Nasgor Katsu', 'category' => 'Makanan', 'price' => 15000],
            ['name' => 'Nasgor Cincang', 'category' => 'Makanan', 'price' => 20000],
            ['name' => 'Nasgor Seafood', 'category' => 'Makanan', 'price' => 25000],
            ['name' => 'Nasgor Pedasmanis', 'category' => 'Makanan', 'price' => 15000],
            ['name' => 'Nasgor Og', 'category' => 'Makanan', 'price' => 15000],
            ['name' => 'Nasgor Udang', 'category' => 'Makanan', 'price' => 15000],
            ['name' => 'Nasgor Wakatobi', 'category' => 'Makanan', 'price' => 25000],
            ['name' => 'Nasgor Jambi', 'category' => 'Makanan', 'price' => 25000],
            ['name' => 'Nasgor Indramyu', 'category' => 'Makanan', 'price' => 25000],

            // Minuman
            ['name' => 'Es Jeruk', 'category' => 'Minuman', 'price' => 5000],
            ['name' => 'Capucino', 'category' => 'Minuman', 'price' => 5000],
            ['name' => 'Pop Strobery', 'category' => 'Minuman', 'price' => 5000],
            ['name' => 'Pop Taro', 'category' => 'Minuman', 'price' => 5000],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}