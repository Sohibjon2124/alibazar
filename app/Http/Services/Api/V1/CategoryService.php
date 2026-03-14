<?php

namespace App\Http\Services\Api\V1;

use App\Http\Repositories\Api\V1\CategoryRepository;
use App\Http\Requests\Api\V1\Category\StoreRequest;
use App\Http\Requests\Api\V1\Category\UpdateRequest;
use App\Http\Resources\Api\V1\CategoryResource;
use App\Http\Resources\Api\V1\ProductResource;
use App\Models\Category;

class CategoryService
{
    private CategoryRepository $categoryRepository;
    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }
    public function index(): array
    {
        $categories = $this->categoryRepository->getAll();
        return CategoryResource::collection($categories)->resolve();
    }

    public function products(Category $category): array
    {
        return ProductResource::collection($category->products->where('status', '=', '1'))->resolve();
    }

    public function store(StoreRequest $storeRequest)
    {
        return $this->categoryRepository->store($storeRequest->validated());
    }

    public function find($request)
    {
        return $this->categoryRepository->findById($request->validated()['category_id']);
    }
    public function update(Category $category, UpdateRequest $request)
    {
        $this->categoryRepository->update($category, $request->validated());
    }
}
