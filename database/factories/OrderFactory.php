<?php

namespace Database\Factories;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'order_number' => 'DND-'.now()->format('Ymd').'-'.Str::upper(fake()->bothify('###')),
            'status' => OrderStatus::Pending->value,
            'guest_email' => null,
            'guest_phone' => null,
            'guest_name' => null,
            'shipping_address' => fake()->streetAddress(),
            'shipping_house_number' => fake()->buildingNumber(),
            'shipping_zip' => fake()->postcode(),
            'shipping_city' => fake()->city(),
            'total_amount' => fake()->randomFloat(2, 3000, 50000),
            'shipping_cost' => 500,
            'payment_method' => 'cash_on_delivery',
        ];
    }

    public function guest(): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => null,
            'guest_email' => fake()->safeEmail(),
            'guest_phone' => fake()->phoneNumber(),
            'guest_name' => fake()->name(),
        ]);
    }

    public function processing(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => OrderStatus::Processing->value,
        ]);
    }

    public function shipped(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => OrderStatus::Shipped->value,
        ]);
    }

    public function delivered(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => OrderStatus::Delivered->value,
        ]);
    }
}
