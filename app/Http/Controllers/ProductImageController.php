<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductImageController extends Controller
{
    // List all images
    public function index()
    {
        return response()->json(ProductImage::all());
    }

    // Show a single image
    public function show($id)
    {
        $image = ProductImage::find($id);
        if (!$image) return response()->json(['message' => 'Image not found'], 404);
        return response()->json($image);
    }

    // Store a new image
    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'image' => 'required|image|max:5000', // file input
            'is_primary' => 'boolean',
        ]);

        // Upload the image file
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('product-images', 'public');
            $data['image_path'] = $path;
        }

        $image = ProductImage::create($data);

        return response()->json($image, 201);
    }

    // Update an existing image
    public function update(Request $request, $id)
    {
        $image = ProductImage::find($id);
        if (!$image) return response()->json(['message' => 'Image not found'], 404);

        $data = $request->validate([
            'image' => 'sometimes|image|max:2048', // optional file
            'is_primary' => 'boolean',
        ]);

        // Upload new image if provided
        if ($request->hasFile('image')) {
            // Delete old image file if exists
            if ($image->image_path && Storage::disk('public')->exists($image->image_path)) {
                Storage::disk('public')->delete($image->image_path);
            }

            $path = $request->file('image')->store('product-images', 'public');
            $data['image_path'] = $path;
        }

        $image->update($data);

        return response()->json($image);
    }

    // Delete an image
    public function destroy($id)
    {
        $image = ProductImage::find($id);
        if (!$image) return response()->json(['message' => 'Image not found'], 404);

        // Delete the image file
        if ($image->image_path && Storage::disk('public')->exists($image->image_path)) {
            Storage::disk('public')->delete($image->image_path);
        }

        $image->delete();

        return response()->json(['message' => 'Image deleted']);
    }
}
