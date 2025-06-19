<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\AddPromotionProductRequest;
use App\Http\Requests\Api\V1\UpdatePromotionProductRequest;
use App\Http\Resources\Api\V1\ProductResource;
use App\Http\Resources\Api\V1\PromotionResource;
use App\Http\Services\PromotionProductService;
use App\Models\ProductPromotion;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PromotionProductController extends Controller
{
    private PromotionProductService $service;

    public function __construct()
    {
        $this->service = new PromotionProductService;
    }

    public function index(): array
    {
        $promotions = ProductPromotion::where('end_date', '>=', Carbon::now())
            ->with('product')->get();

        return $this->service->index($promotions);
    }

    public function store(AddPromotionProductRequest $request): array|JsonResponse
    {
        $promotion = $request->validated();
        return $this->service->store($promotion);
    }

    public function update(UpdatePromotionProductRequest $request): array
    {
        $newPromotion = $request->validated();

        return $this->service->update($newPromotion);
    }
}
