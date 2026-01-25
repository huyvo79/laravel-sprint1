<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Variant;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        Product::factory(50)->create()->each(function ($product) {
            $numberOfVariants = rand(1, 5);

            Variant::factory($numberOfVariants)->create([
                'product_id' => $product->id
            ]);
        });
    }
}
