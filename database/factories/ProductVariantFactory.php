<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProductVariant>
 */
class ProductVariantFactory extends Factory
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
            'size_label' => fake()->randomElement(['30ml', '50ml', '60ml', '80ml', '100ml', '150ml', '200ml']),
            'sku' => strtoupper(fake()->bothify('???-####')),
            'is_active' => true,
            'is_available' => true,
            'stock_quantity' => fake()->numberBetween(5, 100),
        ];
    }

    public function noSize(): static
    {
        return $this->state(fn (array $attributes) => [
            'size_label' => null,
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
