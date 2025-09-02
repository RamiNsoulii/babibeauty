<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\BeautyExpert;
use Illuminate\Http\Request;

class BeautyExpertController extends Controller
{
    public function index()
    {
        $experts = BeautyExpert::with('user', 'bookings', 'reviews')->get();
        return response()->json($experts);
    }

    public function show($id)
    {
        $expert = BeautyExpert::with('user', 'bookings', 'reviews')->find($id);
        if (!$expert) {
            return response()->json(['message' => 'Beauty Expert not found'], 404);
        }
        return response()->json($expert);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'bio' => 'nullable|string',
            'specialization' => 'nullable|string|max:255',
            'experience_years' => 'nullable|integer|min:0',
            'hourly_rate' => 'nullable|numeric|min:0',
        ]);

        $expert = BeautyExpert::create($data);

        return response()->json($expert, 201);
    }

    public function update(Request $request, $id)
    {
        $expert = BeautyExpert::find($id);
        if (!$expert) return response()->json(['message' => 'Beauty Expert not found'], 404);

        $data = $request->validate([
            'bio' => 'nullable|string',
            'specialization' => 'nullable|string|max:255',
            'experience_years' => 'nullable|integer|min:0',
            'hourly_rate' => 'nullable|numeric|min:0',
        ]);

        $expert->update($data);

        return response()->json($expert);
    }

    public function destroy($id)
    {
        $expert = BeautyExpert::find($id);
        if (!$expert) return response()->json(['message' => 'Beauty Expert not found'], 404);

        $expert->delete();

        return response()->json(['message' => 'Beauty Expert deleted']);
    }
}
