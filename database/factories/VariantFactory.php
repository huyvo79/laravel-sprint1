<?php

namespace Database\Factories;
use App\Models\Product;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Variant>
 */
class VariantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'title' => 'Temporary Title', 
            'price' => fake()->randomFloat(2, 100, 1000),
            'compare_at_price' => fake()->randomFloat(2, 1100, 2000),
            'position' => fake()->numberBetween(1, 10),
            'option_1' => fake()->word(),
            'option_2' => fake()->word(),
            'option_3' => fake()->word(),
            'inventory_quantity' => fake()->numberBetween(0, 100),
        ];
    }
}
