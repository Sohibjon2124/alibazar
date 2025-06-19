<?php

namespace App\Http\Services;

use App\Http\Resources\Api\V1\ProductResource;
use App\Http\Resources\Api\V1\PromotionResource;
use App\Models\ProductPromotion;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

class PromotionProductService
{
    public function index($promotions): array
    {
        $products = $promotions->pluck('product')->filter();
        return ProductResource::collection($products)->resolve();
    }

    public function store($promotion): array|JsonResponse
    {

        $actualPromotionProduct = ProductPromotion::where('product_id', '=', $promotion['product_id'])
            ->where('end_date', '>=', Carbon::now())->get();

        if ($actualPromotionProduct->count() > 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'Невозможно добавить: для этого продукта уже назначена акция'
            ], 422);
        }

        $promotion = ProductPromotion::create($promotion);

        return PromotionResource::make($promotion)->resolve();
    }

    public function update($newPromotion): array|JsonResponse
    {
        $promotion = ProductPromotion::where('product_id', '=', $newPromotion['product_id'])
            ->where('end_date', '>=', Carbon::now())
            ->first();

        if (!$promotion) {
            return response()->json([
                'status' => 'error',
                'message' => 'Акция для этого продукта не найдена'
            ], 404);
        }

        $promotion->update([
            'price' => $newPromotion['price'],
            'end_date' => $newPromotion['end_date']
        ]);

        $promotion->refresh();

        return PromotionResource::make($promotion)->resolve();
    }
}
