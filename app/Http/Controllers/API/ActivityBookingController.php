<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\ActivityBooking;
use App\Models\ActivityParticipant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ActivityBookingController extends Controller
{
    /**
     * Store a newly created booking in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'activity_id' => 'required|exists:activities,id',
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'activity_date' => 'required|date',
            'activity_time' => 'required',
            'participants' => 'required|array|min:1',
            'participants.*.name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Get user if logged in, otherwise null (for guest bookings)
            $user = auth()->check() ? auth()->user() : null;
            $userId = $user ? $user->id : null;

            // Create the booking
            $booking = ActivityBooking::create([
                'user_id' => $userId,
                'activity_id' => $request->activity_id,
                'ticket_count' => $request->quantity,
                'unit_price' => $request->unit_price,
                'total_price' => $request->total,
                'booking_date' => now(),
                'activity_date' => $request->activity_date,
                'activity_time' => $request->activity_time,
                'status' => 'confirmed', // Default to confirmed for simplicity
                'payment_status' => 'paid',
                'booking_number' => 'BK' . now()->format('YmdHis') . rand(1000, 9999),
                'notes' => $request->notes ?? null,
            ]);

            // Save participants
            foreach ($request->participants as $participantData) {
                ActivityParticipant::create([
                    'activity_id' => $request->activity_id,
                    'booking_id' => $booking->id,
                    'name' => $participantData['name'] ?? 'Guest',
                    'email' => $participantData['email'] ?? null,
                    'phone' => $participantData['phone'] ?? null,
                ]);
            }

            DB::commit();

            // Return success response
            return response()->json([
                'success' => true,
                'message' => 'Booking created successfully',
                'data' => [
                    'booking_id' => $booking->id,
                    'booking_number' => $booking->booking_number,
                ]
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            // Log the error
            \Log::error('Booking creation error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'error' => 'Failed to create booking: ' . $e->getMessage()
            ], 500);
        }
    }
}
