<?php

namespace Database\Factories;

use App\Models\ProductVariant;
use App\Models\Promotion;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Promotion>
 */
class PromotionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startsAt = fake()->dateTimeBetween('-5 days', '+5 days');
        $endsAt = fake()->dateTimeBetween($startsAt, '+30 days');

        return [
            'product_variant_id' => ProductVariant::factory(),
            'sale_price' => fake()->randomFloat(2, 2000, 18000),
            'starts_at' => $startsAt,
            'ends_at' => $endsAt,
        ];
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'starts_at' => now()->subDays(2),
            'ends_at' => now()->addDays(10),
        ]);
    }

    public function expired(): static
    {
        return $this->state(fn (array $attributes) => [
            'starts_at' => now()->subDays(40),
            'ends_at' => now()->subDays(10),
        ]);
    }

    public function upcoming(): static
    {
        return $this->state(fn (array $attributes) => [
            'starts_at' => now()->addDays(5),
            'ends_at' => now()->addDays(25),
        ]);
    }
}
