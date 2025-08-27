<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['brand', 'categories', 'images', 'reviews'])->get();
        return response()->json($products);
    }

    public function show($id)
    {
        $product = Product::with(['brand', 'categories', 'images', 'reviews'])->find($id);
        if (!$product) return response()->json(['message' => 'Product not found'], 404);
        return response()->json($product);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'brand_id' => 'nullable|exists:brands,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_ids' => 'nullable|array',
            'category_ids.*' => 'exists:categories,id',
        ]);

        $product = Product::create($data);

        // Sync categories if any
        if (!empty($data['category_ids'])) {
            $product->categories()->sync($data['category_ids']);
        }

        return response()->json($product->load(['brand', 'categories', 'images', 'reviews']), 201);
    }

    public function update(Request $request, $id)
    {
        $product = Product::find($id);
        if (!$product) return response()->json(['message' => 'Product not found'], 404);

        $data = $request->validate([
            'brand_id' => 'nullable|exists:brands,id',
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'price' => 'sometimes|numeric|min:0',
            'stock' => 'sometimes|integer|min:0',
            'category_ids' => 'nullable|array',
            'category_ids.*' => 'exists:categories,id',
        ]);

        $product->update($data);

        if (array_key_exists('category_ids', $data)) {
            $product->categories()->sync($data['category_ids']);
        }

        return response()->json($product->load(['brand', 'categories', 'images', 'reviews']));
    }

    public function destroy($id)
    {
        $product = Product::find($id);
        if (!$product) return response()->json(['message' => 'Product not found'], 404);

        $product->delete();

        return response()->json(['message' => 'Product deleted']);
    }
}
