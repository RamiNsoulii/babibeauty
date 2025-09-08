<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->role === 'admin') {
            // Admin sees all bookings
            $bookings = Booking::with(['customer', 'expert'])->get();
        } else {
            // Customer sees only their own bookings
            $bookings = Booking::with(['customer', 'expert'])
                ->where('customer_id', $user->id)
                ->get();
        }

        return response()->json($bookings);
    }

    public function show(Request $request, $id)
    {
        $user = $request->user();
        $booking = Booking::with(['customer', 'expert'])->find($id);

        if (!$booking) return response()->json(['message' => 'Booking not found'], 404);

        // If not admin and booking doesn't belong to user, deny access
        if ($user->role !== 'admin' && $booking->customer_id !== $user->id) {
            return response()->json(['message' => 'Access denied'], 403);
        }

        return response()->json($booking);
    }

    public function store(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'expert_id' => 'required|exists:beauty_experts,id',
            'booking_date' => 'required|date',
            'status' => 'nullable|string|in:pending,confirmed,completed,cancelled',
        ]);

        // Customer ID is always the logged-in user
        $data['customer_id'] = $user->id;

        $booking = Booking::create($data);

        return response()->json($booking, 201);
    }

    public function update(Request $request, $id)
    {
        $user = $request->user();
        $booking = Booking::find($id);

        if (!$booking) return response()->json(['message' => 'Booking not found'], 404);

        // Only admin or owner can update
        if ($user->role !== 'admin' && $booking->customer_id !== $user->id) {
            return response()->json(['message' => 'Access denied'], 403);
        }

        $data = $request->validate([
            'booking_date' => 'sometimes|date',
            'status' => 'nullable|string|in:pending,confirmed,completed,cancelled',
        ]);

        $booking->update($data);

        return response()->json($booking);
    }

    public function destroy(Request $request, $id)
    {
        $user = $request->user();
        $booking = Booking::find($id);

        if (!$booking) return response()->json(['message' => 'Booking not found'], 404);

        // Only admin or owner can delete
        if ($user->role !== 'admin' && $booking->customer_id !== $user->id) {
            return response()->json(['message' => 'Access denied'], 403);
        }

        $booking->delete();

        return response()->json(['message' => 'Booking deleted']);
    }
}
