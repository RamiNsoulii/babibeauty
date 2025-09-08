<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // ===========================
    // List Orders
    // ===========================
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->role === 'admin') {
            // Admin sees all orders
            $orders = Order::with(['user', 'orderItems.product'])->get();
        } else {
            // Customers only see their own orders
            $orders = Order::with(['user', 'orderItems.product'])
                ->where('user_id', $user->id)
                ->get();
        }

        return response()->json($orders);
    }

    // ===========================
    // Show Single Order
    // ===========================
    public function show(Request $request, $id)
    {
        $user = $request->user();
        $order = Order::with(['user', 'orderItems.product'])->find($id);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        // Customers can only see their own order
        if ($user->role !== 'admin' && $order->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($order);
    }

    // ===========================
    // Create Order
    // ===========================
    public function store(Request $request)
    {
        $user = $request->user(); // logged-in customer

        $data = $request->validate([
            'order_items' => 'required|array|min:1',
            'order_items.*.product_id' => 'required|exists:products,id',
            'order_items.*.quantity' => 'required|integer|min:1',
        ]);

        $totalPrice = 0;

        // Attach product price to each item
        $orderItems = [];
        foreach ($data['order_items'] as $item) {
            $product = \App\Models\Product::find($item['product_id']);
            $itemPrice = $product->price * $item['quantity'];
            $totalPrice += $itemPrice;

            $orderItems[] = [
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $product->price, // price per item
            ];
        }

        $order = Order::create([
            'user_id' => $user->id,
            'status' => 'pending',
            'total_price' => $totalPrice,
        ]);

        // Save order items with price included
        foreach ($orderItems as $item) {
            $order->orderItems()->create($item);
        }

        return response()->json($order->load(['orderItems.product']), 201);
    }


    // ===========================
    // Update Order
    // ===========================
    public function update(Request $request, $id)
    {
        $user = $request->user();
        $order = Order::find($id);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        // Customers can’t update others’ orders
        if ($user->role !== 'admin' && $order->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $data = $request->validate([
            'status' => 'sometimes|string|in:pending,paid,shipped,completed,cancelled',
            'total_price' => 'sometimes|numeric|min:0',
        ]);

        $order->update($data);

        return response()->json($order);
    }

    // ===========================
    // Delete Order
    // ===========================
    public function destroy(Request $request, $id)
    {
        $user = $request->user();
        $order = Order::find($id);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        // Customers can’t delete others’ orders
        if ($user->role !== 'admin' && $order->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $order->delete();

        return response()->json(['message' => 'Order deleted']);
    }
}
