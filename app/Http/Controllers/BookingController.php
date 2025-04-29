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
            'participants' => 'required|array|min:1',
            'participants.*.name' => 'required|string|max:255',
        ]);

        // Begin transaction for data integrity
        \DB::beginTransaction();

        try {
            // Create the main booking
            $booking = new \App\Models\Booking();
            $booking->user_id = auth()->id();
            $booking->activity_id = $request->activity_id;
            $booking->ticket_count = $request->ticket_count;
            $booking->total_price = $request->total_price;
            $booking->discount_amount = $request->discount_amount ?? 0;
            $booking->loyalty_points_earned = round($request->total_price);
            $booking->loyalty_points_used = $request->loyalty_points_used ?? 0;
            $booking->status = $request->status;
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
                    'amount' => $request->total_price,
                    'payment_method' => $request->payment_info['payment_method'],
                    'status' => 'completed',
                    'transaction_id' => 'TR-' . time() . '-' . rand(1000, 9999),
                ]);
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
}
