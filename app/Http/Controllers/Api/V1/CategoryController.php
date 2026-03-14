<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Category\StoreRequest;
use App\Http\Requests\Api\V1\Category\UpdateRequest;
use App\Http\Resources\Api\V1\CategoryResource;
use App\Http\Resources\Api\V1\ProductResource;
use App\Http\Services\Api\V1\CategoryService;
use App\Models\Category;
use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    private CategoryService $categoryService;
    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }
    public function index(): JsonResponse
    {
        return response()->json($this->categoryService->index());
    }

    public function products(Category $category): JsonResponse
    {
        return response()->json($this->categoryService->products($category));
    }

    public function store(StoreRequest $storeRequest)
    {
        $category = $this->categoryService->store($storeRequest);

        return CategoryResource::make($category)->resolve();
    }

    public function update(UpdateRequest $request)
    {

        $category = $this->categoryService->find($request);
        
        $this->categoryService->update($category, $request);

        $category->refresh();

        return CategoryResource::make($category)->resolve();
    }
}
