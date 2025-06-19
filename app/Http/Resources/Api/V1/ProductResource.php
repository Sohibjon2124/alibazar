<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
            'category_id' => $this->category_id,
            'count' => $this->count,
            'image' => $this->image,
            'description' => $this->description,
            'promotion_price' => $this->promotion != null ? $this->promotion->price : '' ,
            'on_basket'=> auth()->user()->baskets()->where('product_id',$this->id)->first()?->count ?? 0
        ];
    }
}
