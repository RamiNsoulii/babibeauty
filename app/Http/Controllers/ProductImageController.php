<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ProductImage;
use Illuminate\Http\Request;

class ProductImageController extends Controller
{
    public function index()
    {
        return response()->json(ProductImage::all());
    }

    public function show($id)
    {
        $image = ProductImage::find($id);
        if (!$image) return response()->json(['message' => 'Image not found'], 404);
        return response()->json($image);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'image_path' => 'required|string|max:255',
            'is_primary' => 'boolean',
        ]);

        $image = ProductImage::create($data);

        return response()->json($image, 201);
    }

    public function update(Request $request, $id)
    {
        $image = ProductImage::find($id);
        if (!$image) return response()->json(['message' => 'Image not found'], 404);

        $data = $request->validate([
            'image_path' => 'sometimes|string|max:255',
            'is_primary' => 'boolean',
        ]);

        $image->update($data);

        return response()->json($image);
    }

    public function destroy($id)
    {
        $image = ProductImage::find($id);
        if (!$image) return response()->json(['message' => 'Image not found'], 404);

        $image->delete();

        return response()->json(['message' => 'Image deleted']);
    }
}
