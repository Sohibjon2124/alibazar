<?php

namespace Database\Seeders;

use App\Models\Basket;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\OrderStatus;
use App\Models\PopularProduct;
use App\Models\Product;
use App\Models\ProductColor;
use App\Models\ProductPromotion;
use App\Models\ProductSize;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Category::factory()->count(10)->create();
        Product::factory()->count(30)->create();
        ProductColor::factory()->count(10)->create();
        PopularProduct::factory()->count(10)->create();
        ProductSize::factory()->count(10)->create();
        ProductPromotion::factory()->count(10)->create();

        $statuses = [
            ['name' => 'Новый'],
            ['name' => 'В обработке'],
            ['name' => 'Отгружен'],
            ['name' => 'Завершен'],
        ];

        foreach ($statuses as $status) {
            if (OrderStatus::where('name', '=', $status)->get()->count() == 0) {
                OrderStatus::create($status);
            }
        }

        Basket::factory()->count(10)->create();
        Order::factory()->count(10)->create();
        OrderProduct::factory()->count(40)->create();
    }
}
