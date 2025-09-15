<?php

namespace App\Http\Controllers;

use App\Models\BeautyExpert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

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

        // validate data for creating user + expert profile
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'bio' => 'nullable|string',
            'specialization' => 'nullable|string|max:255',
            'experience_years' => 'nullable|integer|min:0',
            'hourly_rate' => 'nullable|numeric|min:0',
        ]);

        // Step 1: create user with role = expert
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'role' => 'expert',
        ]);

        // Step 2: create expert profile linked to this user
        $expert = BeautyExpert::create([
            'user_id' => $user->id,
            'bio' => $data['bio'] ?? null,
            'specialization' => $data['specialization'] ?? null,
            'experience_years' => $data['experience_years'] ?? 0,
            'hourly_rate' => $data['hourly_rate'] ?? 0,
        ]);

        return response()->json([
            'message' => 'Expert created successfully',
            'user' => $user,
            'expert' => $expert,
        ], 201);
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
