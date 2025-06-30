<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'user_id' => $this->user_id,
            'delivery_type' => $this->delivery_type,
            'delivery_price' => $this->delivery_price,
            'status' => $this->status->id,
            'products' => OrderProductsResource::collection($this->products)
        ];
    }
}
