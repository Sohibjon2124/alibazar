<?php

namespace Database\Factories;

use App\Models\OrderStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
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
            'user_id' => User::inRandomOrder()->first()->id,
            'delivery_type' => '1',
            'delivery_price' => $this->faker->randomFloat('', '', 40),
            'delivery_address' => $this->faker->address,
            'order_status_id' => OrderStatus::inRandomOrder()->first()->id
        ];
    }
}
