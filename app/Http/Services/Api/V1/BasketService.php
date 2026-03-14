<?php

namespace App\Http\Services\Api\V1;

use App\Http\Repositories\Api\V1\BasketRepository;
use App\Http\Repositories\Api\V1\BasketRepositoryInterface;
use App\Http\Requests\Api\V1\Basket\DeleteRequest;
use App\Http\Requests\Api\V1\Basket\StoreRequest;
use App\Http\Requests\Api\V1\Basket\UpdateRequest;
use App\Http\Resources\Api\V1\BasketResource;
use App\Models\User;

class BasketService
{
    private User $user;
    private BasketRepositoryInterface $basketRepository;

    public function __construct(BasketRepositoryInterface $basketRepository)
    {
        $this->basketRepository = $basketRepository;
    }

    public function setUser(User $user)
    {
        $this->user = $user;
        $this->basketRepository->setUser($user);
        return $this;
    }
    public function index()
    {
        return BasketResource::collection($this->user->baskets)->resolve();
    }

    public function store(StoreRequest $request): array
    {
        $this->basketRepository->store($request->validated());

        return $this->index();
    }

    public function update(UpdateRequest $request): array
    {

        $basket = $this->basketRepository->find($request->validated()['id']);

        if (!$basket) {
            return [
                'status' => 'error',
                'message' => 'Эта корзина не принадлежит вам'
            ];
        }

        $this->basketRepository->update($basket, $request->validated());

        $basket->refresh();

        return BasketResource::make($basket)->resolve();
    }


    public function delete(DeleteRequest $request): array
    {
        $basket = $this->basketRepository->find($request->validated()['id']);

        if (!$basket) {
            return [
                'status' => 'error',
                'message' => 'Эта корзина не принадлежит вам'
            ];
        }

        $this->basketRepository->delete($basket);

        return $this->index();
    }
}
