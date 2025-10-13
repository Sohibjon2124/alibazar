<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Basket\DeleteRequest;
use App\Http\Requests\Api\V1\Basket\StoreRequest;
use App\Http\Requests\Api\V1\Basket\UpdateRequest;
use App\Http\Resources\Api\V1\BasketResource;
use App\Models\Basket;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use PHPUnit\Runner\Baseline\Baseline;

class BasketController extends Controller
{

    private $user;

    public function __construct(Request $request)
    {
        $this->user = $request->user();
    }
    public function index(): array
    {
        return BasketResource::collection($this->user->baskets)->resolve();
    }

    public function store(StoreRequest $request): array
    {
        $newBasket = $request->validated();

        Basket::firstOrCreate([
            'user_id' => $this->user->id,
            'product_id' => $newBasket['product_id']
        ], [
            'user_id' => $this->user->id,
            'product_id' => $newBasket['product_id'],
            'count' => $newBasket['count'],
            'color' => $newBasket['color'],
            'size' => $newBasket['size'],
        ]);

        return BasketResource::collection($this->user->baskets)->resolve();
    }

    public function update(UpdateRequest $request): array|JsonResponse
    {
        $newBasket = $request->validated();

        $basket = $this->user->baskets()->find($newBasket['id']);

        if (!$basket) {
            return response()->json([
                'status' => 'error',
                'message' => 'Эта корзина не принадлежит вам'
            ]);
        }

        $basket->update([
            'count' => $newBasket['count'],
            'color' => $newBasket['color'],
            'size' => $newBasket['size']
        ]);

        $basket->refresh();

        return BasketResource::make($basket)->resolve();
    }

    public function delete(DeleteRequest $request): array|JsonResponse
    {
        $basket = $request->validated();
        $basket = $this->user->baskets()->find($basket['id']);

        if (!$basket) {
            return response()->json([
                'status' => 'error',
                'message' => 'Эта корзина не принадлежит вам'
            ]);
        }

        $basket->delete();

        return BasketResource::collection($this->user->baskets)->resolve();
    }
}
