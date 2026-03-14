<?php

namespace App\Http\Repositories\Api\V1;

use App\Models\Basket;
use App\Models\User;


interface BasketRepositoryInterface
{
    public function setUser(User $user);

    public function store($basket);

    public function find($basketId);
    public function update(Basket $basket, array $newBasket);
    public function delete(Basket $basket);
}
