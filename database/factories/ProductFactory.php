<?php

namespace Database\Factories;

use App\Models\ProductCategory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->superadmin()->create()->id,
            'product_category_id' => ProductCategory::factory()->create()->id,
            'name' => fake()->word(),
            'description' => fake()->sentence(),
            'sku' => fake()->unique()->ean13(),
            'quantity' => fake()->numberBetween(1, 100),
            'price' => fake()->randomFloat(2, 1, 100),
            'image' => null,
        ];
    }
}
