<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\BookingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ActivityBookingController extends Controller
{
    protected $bookingService;

    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    /**
     * Store a newly created booking in storage.
     */
    public function store(Request $request)
    {
        // Log incoming request for debugging
        Log::info('ActivityBookingController: Booking request received', [
            'request_data' => $request->all()
        ]);

        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'activity_id' => 'required|exists:activities,id',
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'participants' => 'required|array|min:1',
            'participants.*.name' => 'required|string|max:255',
            'loyalty_points_used' => 'nullable|integer|min:0',
            'use_referral_credit' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            Log::error('ActivityBookingController: Validation failed', [
                'errors' => $validator->errors()->toArray()
            ]);
            
            return response()->json([
                'success' => false,
                'error' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Process the booking using the BookingService
        $user = auth()->user();
        $result = $this->bookingService->createBooking($request->all(), $user);

        // Return appropriate response based on the booking result
        if ($result['success']) {
            return response()->json($result, 201);
        } else {
            return response()->json($result, 500);
        }
    }
}
