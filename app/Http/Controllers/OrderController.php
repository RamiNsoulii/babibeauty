<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['user', 'orderItems.product'])->get();
        return response()->json($orders);
    }

    public function show($id)
    {
        $order = Order::with(['user', 'orderItems.product'])->find($id);
        if (!$order) return response()->json(['message' => 'Order not found'], 404);
        return response()->json($order);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'status' => 'nullable|string|in:pending,paid,shipped,completed,cancelled',
            'total_price' => 'required|numeric|min:0',
            'order_items' => 'required|array|min:1',
            'order_items.*.product_id' => 'required|exists:products,id',
            'order_items.*.quantity' => 'required|integer|min:1',
            'order_items.*.price' => 'required|numeric|min:0',
        ]);

        // Create order
        $order = Order::create([
            'user_id' => $data['user_id'],
            'status' => $data['status'] ?? 'pending',
            'total_price' => $data['total_price'],
        ]);

        // Create order items
        foreach ($data['order_items'] as $item) {
            $order->orderItems()->create($item);
        }

        return response()->json($order->load(['orderItems.product']), 201);
    }

    public function update(Request $request, $id)
    {
        $order = Order::find($id);
        if (!$order) return response()->json(['message' => 'Order not found'], 404);

        $data = $request->validate([
            'status' => 'sometimes|string|in:pending,paid,shipped,completed,cancelled',
            'total_price' => 'sometimes|numeric|min:0',
            // Updating order items not handled here for simplicity
        ]);

        $order->update($data);

        return response()->json($order);
    }

    public function destroy($id)
    {
        $order = Order::find($id);
        if (!$order) return response()->json(['message' => 'Order not found'], 404);

        $order->delete();

        return response()->json(['message' => 'Order deleted']);
    }
}
