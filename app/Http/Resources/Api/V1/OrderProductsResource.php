<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderProductsResource extends JsonResource
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
            'category' => $this->category_id,                  // из products
            'price' => $this->price,                  // цена продукта (из products)
            'count' => $this->pivot->count,           // количество в заказе (из pivot)
            'order_price' => $this->pivot->price,     // цена в заказе (из pivot)
            'color' => $this->pivot->color,
            'size' => $this->pivot->size,
        ];
    }
}
