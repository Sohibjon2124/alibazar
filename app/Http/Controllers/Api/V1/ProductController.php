<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Product\StoreRequest;
use App\Http\Requests\Api\V1\Product\UpdateRequest;
use App\Http\Requests\Api\V1\Products\Request as ProductsRequest;
use App\Http\Requests\Api\V1\Products\SearchRequest;
use App\Http\Resources\Api\V1\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    public function index(ProductsRequest $request)
    {
        $data = $request->validated();
        $perPage = $data['per_page'] ?? 20;

        $products = Product::paginate($perPage);

        return response()->json([
            'data' => ProductResource::collection($products->items()),
            'pagination' => [
                'total' => $products->total(),
                'per_page' => $products->perPage(),
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'from' => $products->firstItem(),
                'to' => $products->lastItem()
            ],
        ]);
    }

    public function search(SearchRequest $request)
    {
        $data = $request->validated();

        $searchItem = $data['search'];
        $perPage = $data['per_page'] ?? 20;

        $products = Product::where('name', 'like', '%' . $searchItem . '%')
            ->orWhere('description', 'like', '%' . $searchItem . '%')
            ->paginate($perPage);

        return response()->json([
            'data' => ProductResource::collection($products->items()),
            'pagination' => [
                'total' => $products->total(),
                'per_page' => $products->perPage(),
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'from' => $products->firstItem(),
                'to' => $products->lastItem()
            ],
        ]);
    }
    public function store(StoreRequest $request): array
    {
        $newProduct = $request->validated();
        $producut = Product::create($newProduct);

        return ProductResource::make($producut)->resolve();
    }

    public function show(Product $product): array
    {
        return ProductResource::make($product)->resolve();
    }

    public function update(UpdateRequest $request): array
    {
        $updatedProduct = $request->validated();
        $product = Product::find($updatedProduct['id']);
        $product->update($updatedProduct);
        $product->refresh();

        return ProductResource::make($product)->resolve();
    }
}
