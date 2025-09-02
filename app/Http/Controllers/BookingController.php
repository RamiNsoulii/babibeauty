<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with(['customer', 'expert'])->get();
        return response()->json($bookings);
    }

    public function show($id)
    {
        $booking = Booking::with(['customer', 'expert'])->find($id);
        if (!$booking) return response()->json(['message' => 'Booking not found'], 404);
        return response()->json($booking);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_id' => 'required|exists:users,id',
            'expert_id' => 'required|exists:beauty_experts,id',
            'booking_date' => 'required|date',
            'status' => 'nullable|string|in:pending,confirmed,completed,cancelled',
        ]);

        $booking = Booking::create($data);

        return response()->json($booking, 201);
    }

    public function update(Request $request, $id)
    {
        $booking = Booking::find($id);
        if (!$booking) return response()->json(['message' => 'Booking not found'], 404);

        $data = $request->validate([
            'booking_date' => 'sometimes|date',
            'status' => 'nullable|string|in:pending,confirmed,completed,cancelled',
        ]);

        $booking->update($data);

        return response()->json($booking);
    }

    public function destroy($id)
    {
        $booking = Booking::find($id);
        if (!$booking) return response()->json(['message' => 'Booking not found'], 404);

        $booking->delete();

        return response()->json(['message' => 'Booking deleted']);
    }
}
