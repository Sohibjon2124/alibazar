<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductPromotion>
 */
class ProductPromotionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => Product::inRandomOrder()->first()->id,
            'price' => $this->faker->randomFloat(),
            'end_date' => $this->faker->dateTimeBetween('now', '+1 year')->format('Y-m-d')

        ];
    }
}
