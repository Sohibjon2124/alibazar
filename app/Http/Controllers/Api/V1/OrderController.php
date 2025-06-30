<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Orders\StoreRequest;
use App\Http\Requests\Api\V1\Orders\UpdateRequest;
use App\Http\Resources\Api\V1\OrderResource;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class OrderController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        return OrderResource::collection($user->orders);
    }

    public function store(StoreRequest $request)
    {
        $newOrder = $request->validated();

        $order = DB::transaction(function () use ($newOrder) {
            $order = Order::create([
                'user_id' => auth()->user()->id,
                'delivery_type' => $newOrder['delivery_type'],
                'delivery_address' => $newOrder['delivery_address'],
                'delivery_price' => 0,
                'order_status_id' => 1,
            ]);

            foreach ($newOrder['products'] as $orderProduct) {

                $product = Product::find($orderProduct['id']);

                $order->products()->attach(
                    $orderProduct['id'],
                    [
                        'count' => $orderProduct['count'],
                        'price' => $product->price * $orderProduct['count'],
                        'color' => $orderProduct['color'],
                        'size' => $orderProduct['size']
                    ]
                );
            }

            return $order;
        });

        return OrderResource::make($order)->resolve();
    }

    public function update(UpdateRequest $request)
    {
        try {
            $newOrder = $request->validated();

            $order = DB::transaction(function () use ($newOrder) {

                $order = Order::findOrFail($newOrder['order_id']);

                $order->update([
                    'delivery_type' => $newOrder['delivery_type'],
                    'delivery_price' => $newOrder['delivery_price'] ?? 0,
                    'delivery_address' => $newOrder['delivery_address'],
                    'order_status_id' => $newOrder['status_id']
                ]);

                $syncData = [];

                foreach ($newOrder['products'] as $orderProduct) {

                    $product = Product::findOrFail($orderProduct['id']);
                    $syncData[$product->id] = [
                        'count' => $orderProduct['count'],
                        'price' => $product->price * $orderProduct['count'],
                        'color' => $orderProduct['color'],
                        'size' => $orderProduct['size']
                    ];
                }

                $order->products()->sync($syncData);

                return $order;
            });

            $order->refresh();

            return OrderResource::make($order)->resolve();
        } catch (\Throwable $e) {

            return response()->json([
                'status' => 'error',
                'message' => 'Ошибка при обновлении заказа',
                'details' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function delete(Order $order)
    {
        DB::transaction(function () use ($order) {
            $order->products()->detach();
            $order->delete();
        });

        return response(null, 200);
    }
}
