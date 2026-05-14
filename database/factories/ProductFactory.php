<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
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
            'brand_id' => Brand::factory(),
            'name' => fake()->words(3, true).' '.fake()->randomElement(['EDP', 'EDT', 'Parfum']),
            'description' => fake()->paragraphs(3, true),
            'type' => 'simple',
            'gender' => fake()->randomElement(['male', 'female', 'unisex']),
            'is_active' => true,
        ];
    }

    public function bundle(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'bundle',
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
