<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Category\StoreRequest;
use App\Http\Requests\Api\V1\Category\UpdateRequest;
use App\Http\Resources\Api\V1\CategoryResource;
use App\Http\Resources\Api\V1\ProductResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CategoryController extends Controller
{
    public function index(): array
    {
        $categories = Category::where('status', '=', '1')->get();
        return CategoryResource::collection($categories)->resolve();
    }

    public function products(Category $category): array
    {
        return ProductResource::collection($category->products)->resolve();
    }

    public function store(StoreRequest $storeRequest)
    {
        $newCategory = $storeRequest->validated();

        $category = Category::create([
            'name' => $newCategory['name'],
            'status' => '1'
        ]);

        return CategoryResource::make($category)->resolve();
    }

    public function update(UpdateRequest $request)
    {
        $newCategory = $request->validated();
        $category = Category::find($newCategory['category_id']);

        $category->update([
            'name' => $newCategory['name'],
            'status' => $newCategory['status']
        ]);

        $category->refresh();

        return CategoryResource::make($category)->resolve();
    }
}
