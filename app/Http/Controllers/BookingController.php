<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created booking in the database.
     */
    public function store(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'activity_id' => 'required|exists:activities,id',
            'ticket_count' => 'required|integer|min:1',
            'total_price' => 'required|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'status' => 'required|string',
            'payment_status' => 'required|string',
            'loyalty_points_used' => 'nullable|integer|min:0',
            'participants' => 'required|array|min:1',
            'participants.*.name' => 'required|string|max:255',
        ]);

        // Begin transaction for data integrity
        \DB::beginTransaction();

        try {
            $user = auth()->user();
            $pointsUsed = $request->loyalty_points_used ?? 0;

            // Verify user has enough points if they're using points
            if ($pointsUsed > 0) {
                // For backward compatibility, check both available_points and loyalty_points
                $availablePoints = $user->available_points ?? ($user->loyalty_points - $user->used_points);

                if ($availablePoints < $pointsUsed) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Not enough loyalty points available',
                    ], 400);
                }

                // Calculate points value in dollars (1 point = $0.1)
                $pointsService = app(\App\Services\PointsService::class);
                $pointsValueInDollars = $pointsService->pointsToDollars($pointsUsed);
            } else {
                $pointsValueInDollars = 0;
            }

            // Create the main booking
            $booking = new \App\Models\Booking();
            $booking->user_id = $user->id;
            $booking->activity_id = $request->activity_id;
            $booking->ticket_count = $request->ticket_count;
            $booking->total_price = $request->total_price;
            $booking->discount_amount = $request->discount_amount ?? 0;

            // Calculate loyalty points earned (1 point per $1 spent after discounts and point redemption)
            $finalPrice = $booking->total_price - $booking->discount_amount - $pointsValueInDollars;
            $pointsEarned = max(0, values: round($finalPrice));
            // dd($pointsEarned); // Ensure no negative points and round to nearest integer

            $booking->loyalty_points_earned = $pointsEarned;
            $booking->loyalty_points_used = $pointsUsed;
            // Always set status to pending, regardless of what was passed in the request
            $booking->status = 'pending';
            $booking->payment_status = $request->payment_status;
            $booking->save();

            // Link the activity to the booking in the pivot table
            $booking->activities()->attach($request->activity_id);

            // Save participants
            foreach ($request->participants as $participant) {
                \App\Models\ActivityParticipant::create([
                    'booking_id' => $booking->id,
                    'activity_id' => $request->activity_id,
                    'name' => $participant['name'],
                    'email' => $participant['email'] ?? null,
                    'phone' => $participant['phone'] ?? null,
                ]);
            }

            // Create a payment record
            if ($request->has('payment_info')) {
                \App\Models\Payment::create([
                    'booking_id' => $booking->id,
                    'amount' => $finalPrice, // Amount after discounts and point redemption
                    'payment_method' => $request->payment_info['payment_method'],
                    'status' => 'completed',
                    'transaction_id' => 'TR-' . time() . '-' . rand(1000, 9999),
                ]);
            }

            // Update user's loyalty points if points were used or earned
            if ($pointsUsed > 0 || $pointsEarned > 0) {
                $pointsService = app(\App\Services\PointsService::class);

                // Use points first (if applied to this purchase)
                if ($pointsUsed > 0) {
                    $pointsService->usePoints($user, $pointsUsed, 'booking_discount');
                }

                // Then add earned points
                if ($pointsEarned > 0) {
                    $pointsService->addPoints($user, $pointsEarned, 'booking_reward');
                }
            }

            \DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Booking created successfully',
                'booking' => $booking
            ], 201);
        } catch (\Exception $e) {
            \DB::rollBack();

            // Log the error
            \Log::error('Booking creation error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to create booking',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Display the checkout page.
     */
    public function checkout()
    {
        $user = auth()->user();
        $pendingBookings = \App\Models\Booking::where('user_id', $user->id)
            ->where('status', 'pending')
            ->with('activities')
            ->get();

        return view('public.pages.checkout', [
            'pendingBookings' => $pendingBookings,
            'totalAmount' => $pendingBookings->sum('total_price')
        ]);
    }

    /**
     * Cancel a booking
     *
     * @param \App\Models\Booking $booking
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancel(\App\Models\Booking $booking)
    {
        // Check if the booking belongs to the authenticated user
        if (auth()->id() !== $booking->user_id) {
            return redirect()->route('profile.index')
                ->with('error', 'You are not authorized to cancel this booking.');
        }

        // Check if the booking is in a pending status
        if ($booking->status !== 'pending') {
            return redirect()->route('profile.index')
                ->with('error', 'Only pending bookings can be cancelled.');
        }

        // Begin transaction
        \DB::beginTransaction();

        try {
            // Update booking status
            $booking->status = 'cancelled';
            $booking->save();

            // Handle loyalty points adjustments
            $pointsService = app(\App\Services\PointsService::class);

            // 1. If the user earned points from this booking, deduct them
            if ($booking->loyalty_points_earned > 0) {
                $user = $booking->user;
                // Directly update the user's loyalty points by subtracting earned points
                $user->loyalty_points -= $booking->loyalty_points_earned;
                $user->save();

                \Log::info("Deducted {$booking->loyalty_points_earned} points from user {$user->id} due to booking cancellation.");
            }

            // 2. If the user used points for this booking, refund them
            if ($booking->loyalty_points_used > 0) {
                $pointsService->addPoints(
                    $booking->user,
                    $booking->loyalty_points_used,
                    'booking_cancellation_refund'
                );
            }

            // If there was a payment, mark it for refund
            if ($booking->payment_status === 'paid') {
                $booking->payment_status = 'refunded';
                $booking->save();

                // Update any associated payment records
                foreach ($booking->payments as $payment) {
                    $payment->status = 'refunded';
                    $payment->save();
                }
            }

            \DB::commit();

            return redirect()->route('profile.index')
                ->with('success', 'Your booking has been successfully cancelled.');
        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('Booking cancellation error: ' . $e->getMessage());

            return redirect()->route('profile.index')
                ->with('error', 'There was an error cancelling your booking. Please try again or contact support.');
        }
    }
}
