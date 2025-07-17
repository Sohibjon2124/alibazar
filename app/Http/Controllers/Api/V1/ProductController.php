<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Product\StoreRequest;
use App\Http\Requests\Api\V1\Product\UpdateRequest;
use App\Http\Requests\Api\V1\Products\Request as ProductsRequest;
use App\Http\Requests\Api\V1\Products\SearchRequest;
use App\Http\Resources\Api\V1\ProductResource;
use App\Models\Product;
use App\Models\ProductColor;
use App\Models\ProductSize;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    public function index(ProductsRequest $request)
    {
        $data = $request->validated();
        $perPage = $data['per_page'] ?? 20;

        $products = Product::where('status', '=', '1')
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

    public function search(SearchRequest $request)
    {
        $data = $request->validated();

        $searchItem = $data['search'];
        $perPage = $data['per_page'] ?? 20;

        $products = Product::where('status', '=', '1')
            ->where('name', 'like', '%' . $searchItem . '%')
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
        //TODo transaction

        $data = $request->validated();

        $sizes = explode(',', $data['sizes']);
        $colors = explode(',', $data['colors']);

        unset($data['sizes']);
        unset($data['colors']);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public'); // storage/app/public/products
            $data['image'] = $path;
        }

        $data['status'] = '1';

        $product = Product::create($data);

        foreach ($sizes as $size) {
            ProductSize::create([
                'product_id' => $product->id,
                'size' => $size
            ]);
        }

        foreach ($colors as $color) {
            ProductColor::create([
                'product_id' => $product->id,
                'color' => $color
            ]);
        }

        return ProductResource::make($product)->resolve();
    }

    public function show(Product $product): array
    {
        if ($product->status !== '1') {
            abort(404);
        }
        return ProductResource::make($product)->resolve();
    }

    public function update(UpdateRequest $request): array
    {
        $data = $request->validated();
        $product = Product::find($data['id']);

        $sizes = explode(',', $data['sizes']);
        $colors = explode(',', $data['colors']);

        unset($data['sizes']);
        unset($data['colors']);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public'); // storage/app/public/products
            $data['image'] = $path;
        }

        $product->update($data);

        $product->sizes()->delete();
        foreach ($sizes as $size) {
            $product->sizes()->create([
                'product_id' => $product->id,
                'size' => $size
            ]);
        }

        $product->colors()->delete();
        foreach ($colors as $color) {
            $product->colors()->create([
                'product_id' => $product->id,
                'color' => $color
            ]);
        }

        $product->refresh();

        return ProductResource::make($product)->resolve();
    }
}
