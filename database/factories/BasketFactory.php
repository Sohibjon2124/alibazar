<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductColor;
use App\Models\ProductSize;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Basket>
 */
class BasketFactory extends Factory
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
            'product_id' => Product::inRandomOrder()->first()->id,
            'color' => ProductColor::inRandomOrder()->first()->color,
            'size' => ProductSize::inRandomOrder()->first()->size,
            'count' => $this->faker->numberBetween(1, 12)
        ];
    }
}
