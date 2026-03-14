<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Basket\DeleteRequest;
use App\Http\Requests\Api\V1\Basket\StoreRequest;
use App\Http\Requests\Api\V1\Basket\UpdateRequest;
use App\Http\Resources\Api\V1\BasketResource;
use App\Http\Services\Api\V1\BasketService;
use App\Models\Basket;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use PHPUnit\Runner\Baseline\Baseline;

class BasketController extends Controller
{

    private $user;
    private $basketService;

    public function __construct(Request $request, BasketService $basketService)
    {
        $this->user = $request->user();
        $this->basketService = $basketService->setUser($this->user);
    }

    public function index(): array|JsonResponse
    {
        return response()->json($this->basketService->index());
    }

    public function store(StoreRequest $request): array|JsonResponse
    {
        return response()->json($this->basketService->store($request));
    }

    public function update(UpdateRequest $request): array|JsonResponse
    {
        return response()->json($this->basketService->update($request));
    }

    public function delete(DeleteRequest $request): array|JsonResponse
    {
        return response()->json($this->basketService->delete($request));
    }
}
