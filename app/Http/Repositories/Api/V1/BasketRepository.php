<?php

namespace App\Http\Repositories\Api\V1;

use App\Models\Basket;
use App\Models\User;

class BasketRepository implements BasketRepositoryInterface
{

    protected User $user;
    public function setUser(User $user)
    {
        $this->user = $user;
        return $this;
    }

    public function store($basket)
    {
        Basket::firstOrCreate([
            'user_id' => $this->user->id,
            'product_id' => $basket['product_id']
        ], [
            'user_id' => $this->user->id,
            'product_id' => $basket['product_id'],
            'count' => $basket['count'],
            'color' => $basket['color'],
            'size' => $basket['size'],
        ]);
    }

    public function find($basketId)
    {
        return $this->user->baskets()->find($basketId);
    }
    public function update(Basket $basket, array $newBasket)
    {
        $basket->update([
            'count' => $newBasket['count'],
            'color' => $newBasket['color'],
            'size' => $newBasket['size']
        ]);
    }
    public function delete(Basket $basket)
    {
        $basket->delete();
    }
}
