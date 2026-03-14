<?php

namespace App\Http\Repositories\Api\V1;

use App\Http\Requests\Api\V1\Category\UpdateRequest;
use App\Models\Basket;
use App\Models\Category;
use App\Models\User;

class CategoryRepository
{

    public function findById($category): Category
    {
        return Category::find($category);
    }
    public function getAll()
    {
        return Category::where('status', '=', '1')->get();
    }

    public function store(array $request)
    {
        return Category::create([
            'name' => $request['name'],
            'status' => '1'
        ]);
    }

    public function update(Category $category, array $request)
    {
        $category->update([
            'name' => $request['name'],
            'status' => $request['status']
        ]);
    }
}
