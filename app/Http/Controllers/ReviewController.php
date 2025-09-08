<?php

namespace App\Http\Controllers;

use App\Models\BeautyExpert;
use App\Models\Review;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReviewController extends Controller
{
    // Get all reviews
    public function index()
    {
        return response()->json(Review::with('user')->get());
    }

    // Get a single review
    public function show($id)
    {
        $review = Review::with('user')->find($id);
        if (!$review) {
            return response()->json(['message' => 'Review not found'], 404);
        }
        return response()->json($review);
    }

    // Create a review (general)
    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'reviewable_id' => 'required|integer',
            'reviewable_type' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        $review = Review::create($data);

        return response()->json($review, 201);
    }

    // Update a review
    public function update(Request $request, $id)
    {
        $review = Review::find($id);
        if (!$review) {
            return response()->json(['message' => 'Review not found'], 404);
        }

        $data = $request->validate([
            'rating' => 'sometimes|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        $review->update($data);

        return response()->json($review);
    }

    // Delete a review
    public function destroy($id)
    {
        $review = Review::find($id);
        if (!$review) {
            return response()->json(['message' => 'Review not found'], 404);
        }

        $review->delete();

        return response()->json(['message' => 'Review deleted']);
    }

    // Create a review for a product
    public function storeForProduct(Request $request, $productId)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        $data['reviewable_id'] = $productId;
        $data['reviewable_type'] = 'App\\Models\\Product';

        $review = Review::create($data);

        return response()->json($review, 201);
    }

    // Get all reviews for a product
    public function indexForProduct($productId)
    {
        $reviews = Review::with('user')
            ->where('reviewable_id', $productId)
            ->where('reviewable_type', 'App\\Models\\Product')
            ->get();

        return response()->json($reviews);
    }

    // Get all reviews for a BeautyExpert
    public function indexForExpert($expertId)
    {
        $reviews = Review::with('user')
            ->where('reviewable_id', $expertId)
            ->where('reviewable_type', 'App\\Models\\BeautyExpert')
            ->get();

        return response()->json($reviews);
    }

    // Create a review for a BeautyExpert using logged-in user
    public function storeForExpert(Request $request, $expertId)
    {
        // Check if the BeautyExpert exists
        $expert = BeautyExpert::find($expertId);
        if (!$expert) {
            return response()->json(['message' => 'Expert not found'], 404);
        }

        // Validate the request
        $data = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        // Set the reviewable info and logged-in user
        $data['reviewable_id'] = $expertId;
        $data['reviewable_type'] = 'App\\Models\\BeautyExpert';
        $data['user_id'] = $request->user()->id; // automatically use logged-in user

        $review = Review::create($data);

        return response()->json($review, 201);
    }
}
