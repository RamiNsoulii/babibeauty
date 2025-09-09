<?php

namespace App\Http\Controllers;

use App\Models\BeautyExpert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BeautyExpertController extends Controller
{
    // ========== List all beauty experts ==========
    public function index()
    {
        $experts = BeautyExpert::with('user', 'bookings', 'reviews')->get();
        return response()->json($experts);
    }

    // ========== Show single expert ==========
    public function show($id)
    {
        $expert = BeautyExpert::with('user', 'bookings', 'reviews')->find($id);

        if (!$expert) {
            return response()->json(['message' => 'Beauty Expert not found'], 404);
        }

        return response()->json($expert);
    }

    // ========== Admin: Create new expert ==========
    public function store(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            return response()->json(['message' => 'Only admin can create experts'], 403);
        }

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

    // ========== Admin or owner: Update expert ==========
    public function update(Request $request, $id)
    {
        $expert = BeautyExpert::find($id);
        if (!$expert) {
            return response()->json(['message' => 'Beauty Expert not found'], 404);
        }

        // Only admin or owner can update
        if (Auth::user()->role !== 'admin' && Auth::id() !== $expert->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $data = $request->validate([
            'bio' => 'nullable|string',
            'specialization' => 'nullable|string|max:255',
            'experience_years' => 'nullable|integer|min:0',
            'hourly_rate' => 'nullable|numeric|min:0',
        ]);

        $expert->update($data);

        return response()->json($expert);
    }

    // ========== Admin only: Delete expert ==========
    public function destroy($id)
    {
        $expert = BeautyExpert::find($id);
        if (!$expert) {
            return response()->json(['message' => 'Beauty Expert not found'], 404);
        }

        if (Auth::user()->role !== 'admin') {
            return response()->json(['message' => 'Only admin can delete experts'], 403);
        }

        $expert->delete();

        return response()->json(['message' => 'Beauty Expert deleted']);
    }

    // ========== Expert: View own profile ==========
    public function myProfile()
    {
        $expert = BeautyExpert::with('user', 'bookings', 'reviews')
            ->where('user_id', Auth::id())
            ->first();

        if (!$expert) {
            return response()->json(['message' => 'You are not registered as a beauty expert'], 404);
        }

        return response()->json($expert);
    }

    // ========== Expert: Update own profile ==========
    public function updateMyProfile(Request $request)
    {
        $expert = BeautyExpert::where('user_id', Auth::id())->first();
        if (!$expert) {
            return response()->json(['message' => 'You are not registered as a beauty expert'], 404);
        }

        $data = $request->validate([
            'bio' => 'nullable|string',
            'specialization' => 'nullable|string|max:255',
            'experience_years' => 'nullable|integer|min:0',
            'hourly_rate' => 'nullable|numeric|min:0',
        ]);

        $expert->update($data);

        return response()->json($expert);
    }

    // ========== Expert: Get own bookings ==========
    public function myBookings()
    {
        $expert = BeautyExpert::with('bookings')->where('user_id', Auth::id())->first();

        if (!$expert) {
            return response()->json(['message' => 'Beauty Expert profile not found'], 404);
        }

        return response()->json($expert->bookings);
    }

    // ========== Expert: Get own reviews ==========
    public function myReviews()
    {
        $expert = BeautyExpert::with('reviews.user')->where('user_id', Auth::id())->first();

        if (!$expert) {
            return response()->json(['message' => 'Beauty Expert profile not found'], 404);
        }

        return response()->json($expert->reviews);
    }
}
