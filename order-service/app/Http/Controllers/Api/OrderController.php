<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(
        private OrderService $orderService,
    ) {
    }

    public function index(Request $request)
    {
        $orders = Order::query()
            ->where('user_id', $request->user()->id)
            ->with('items')
            ->latest()
            ->paginate(15);

        return OrderResource::collection($orders);
    }

    public function store(StoreOrderRequest $request): OrderResource
    {
        $order = $this->orderService->createOrder(
            $request->user()->id,
            $request->validated('items'),
        );

        return (new OrderResource($order))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Request $request, Order $order): OrderResource
    {
        abort_unless(
            $order->user_id === $request->user()->id,
            404
        );

        return new OrderResource(
            $order->load('items')
        );
    }

    public function cancel(
        Request $request,
        Order $order,
    ): OrderResource {
        abort_unless(
            $order->user_id === $request->user()->id,
            404
        );

        $order = $this->orderService->cancel($order);

        return new OrderResource($order);
    }
}
