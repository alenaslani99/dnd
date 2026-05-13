<?php

namespace Database\Factories;

use App\Models\Price;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Price>
 */
class PriceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_variant_id' => ProductVariant::factory(),
            'amount' => fake()->randomFloat(2, 3000, 25000),
            'currency' => 'RSD',
            'effective_date' => fake()->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
