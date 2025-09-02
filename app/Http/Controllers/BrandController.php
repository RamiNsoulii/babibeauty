<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function index()
    {
        return response()->json(Brand::all());
    }

    public function show($id)
    {
        $brand = Brand::find($id);
        if (!$brand) return response()->json(['message' => 'Brand not found'], 404);
        return response()->json($brand);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|unique:brands,name',
            'description' => 'nullable|string',
        ]);

        $brand = Brand::create($data);
        return response()->json($brand, 201);
    }

    public function update(Request $request, $id)
    {
        $brand = Brand::find($id);
        if (!$brand) return response()->json(['message' => 'Brand not found'], 404);

        $data = $request->validate([
            'name' => 'sometimes|string|unique:brands,name,' . $id,
            'description' => 'nullable|string',
        ]);

        $brand->update($data);
        return response()->json($brand);
    }

    public function destroy($id)
    {
        $brand = Brand::find($id);
        if (!$brand) return response()->json(['message' => 'Brand not found'], 404);

        $brand->delete();
        return response()->json(['message' => 'Brand deleted']);
    }
}
