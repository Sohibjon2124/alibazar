<?php

namespace Database\Factories;

use App\Models\Category;
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
            'name' => $this->faker->name,
            'price' => $this->faker->randomFloat(),
            'category_id' =>Category::inRandomOrder()->first()->id,
            'count'=>$this->faker->numberBetween(0,100),
            'image' => $this->faker->imageUrl(640, 480, 'products', true),
            'description' => $this->faker->text,
            'status'=>'1',
        ];
    }
}
