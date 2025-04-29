<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Booking;
use App\Models\ActivityBooking;
use App\Models\ActivityParticipant;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ActivityBookingController extends Controller
{
    /**
     * Store a newly created booking with participants.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Log the request for debugging
        Log::info('Booking request data:', $request->all());

        // Authentication is now handled by middleware, so we can remove the explicit check here

        // Validate the request
        $validator = Validator::make($request->all(), [
            'activity_id' => 'required|exists:activities,id',
            'quantity' => 'required|integer|min:1|max:10',
            'unit_price' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'activity_date' => 'required|date',
            'activity_time' => 'nullable|string',
            'participants' => 'required|array|min:1',
            'participants.*.name' => 'required|string|max:255',
            'participants.*.phone' => 'nullable|string|max:30',
            'participants.*.email' => 'nullable|email|max:255',
            'payment_info' => 'nullable|array',
            'payment_info.payment_method' => 'nullable|string',
            'payment_info.card_number' => 'nullable|string',
            'payment_info.expiry_date' => 'nullable|string',
            'payment_info.cvv' => 'nullable|string',
            'payment_info.cardholder_name' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            Log::error('Booking validation failed:', $validator->errors()->toArray());
            return response()->json(['success' => false, 'error' => 'Validation failed: ' . implode(', ', $validator->errors()->all())], 422);
        }

        // Get authenticated user - we can be sure this exists because of auth middleware
        $user = Auth::user();
        Log::info('User authenticated successfully', ['user_id' => $user->id, 'email' => $user->email]);

        try {
            // Start a database transaction
            \DB::beginTransaction();

            // Get the activity to ensure it exists
            $activity = \App\Models\Activity::find($request->activity_id);
            if (!$activity) {
                throw new \Exception("Activity not found with ID " . $request->activity_id);
            }

            Log::info('Found activity:', ['id' => $activity->id, 'name' => $activity->name]);

            // Create the booking with activity_id as a foreign key
            $booking = \App\Models\Booking::create([
                'user_id' => $user->id,
                'activity_id' => $activity->id, // Use activity ID from the model to ensure consistency
                'booking_number' => 'JT-' . now()->format('Ymd') . '-' . rand(1000, 9999),
                'ticket_count' => $request->quantity,
                'total_price' => $request->total,
                'discount_amount' => 0,
                'status' => 'confirmed',
                'payment_status' => 'paid',
                'loyalty_points_earned' => ceil($request->total / 10),
                'loyalty_points_used' => 0,
                'notes' => $request->notes ?? null,
            ]);

            Log::info('Booking created:', ['id' => $booking->id, 'booking_number' => $booking->booking_number, 'user_id' => $user->id]);

            // Create activity booking - attach activity using the ID from the model
            Log::info('Attaching activity to booking:', ['activity_id' => $activity->id, 'booking_id' => $booking->id]);
            $booking->activities()->attach($activity->id);

            // Create participants
            foreach ($request->participants as $participantData) {
                Log::info('Creating participant:', $participantData);
                $participant = \App\Models\ActivityParticipant::create([
                    'activity_id' => $activity->id,
                    'booking_id' => $booking->id,
                    'name' => $participantData['name'],
                    'phone' => $participantData['phone'] ?? null,
                    'email' => $participantData['email'] ?? null,
                ]);
            }

            // Create payment record
            Log::info('Creating payment record');
            $payment = \App\Models\Payment::create([
                'booking_id' => $booking->id,
                'payment_method' => $request->payment_info['payment_method'] ?? 'credit_card',
                'transaction_id' => 'TXN-' . uniqid(),
                'amount' => $request->total,
                'currency' => 'USD',
                'status' => 'completed',
            ]);

            // Commit the transaction
            \DB::commit();

            Log::info('Booking process completed successfully');

            return response()->json([
                'success' => true,
                'message' => 'Activity booked successfully!',
                'booking_id' => $booking->id,
                'booking_number' => $booking->booking_number
            ], 201);
        } catch (\Exception $e) {
            // Roll back the transaction on error
            \DB::rollBack();

            Log::error('Error creating booking:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'There was an error processing your booking. Please try again.',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the participants form for a specific activity
     *
     * @param  \App\Models\Activity  $activity
     * @return \Illuminate\Http\Response
     */
    public function showParticipantsForm(Activity $activity)
    {
        return view('public.pages.activityParticipants', compact('activity'));
    }
}
