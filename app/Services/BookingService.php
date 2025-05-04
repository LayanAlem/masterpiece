<?php

namespace App\Services;

use App\Models\Activity;
use App\Models\ActivityBooking;
use App\Models\ActivityParticipant;
use App\Models\Booking;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BookingService
{
    protected $pointsService;
    protected $referralService;

    public function __construct(PointsService $pointsService, ReferralService $referralService)
    {
        $this->pointsService = $pointsService;
        $this->referralService = $referralService;
    }

    /**
     * Create a new booking with proper error handling
     *
     * @param array $bookingData
     * @param User|null $user
     * @return array
     */
    public function createBooking(array $bookingData, ?User $user = null): array
    {
        DB::beginTransaction();

        try {
            // 1. Prepare booking values
            $activityId = $bookingData['activity_id'];
            $activity = Activity::findOrFail($activityId);
            $userId = $user ? $user->id : null;
            $quantity = $bookingData['quantity'];
            $unitPrice = $bookingData['unit_price'];
            $total = $bookingData['total'];
            $participants = $bookingData['participants'];

            // Check if there's enough capacity for this booking
            if ($quantity > $activity->remaining_capacity) {
                Log::warning('Booking capacity exceeded', [
                    'activity_id' => $activity->id,
                    'requested_tickets' => $quantity,
                    'remaining_capacity' => $activity->remaining_capacity
                ]);

                return [
                    'success' => false,
                    'error' => 'Not enough seats available for this activity',
                    'details' => "You requested {$quantity} seats, but only {$activity->remaining_capacity} seats are available."
                ];
            }

            // 2. Process discounts
            $loyaltyPointsUsed = $bookingData['loyalty_points_used'] ?? 0;
            $useReferralCredit = $bookingData['use_referral_credit'] ?? false;

            $pointsDiscount = 0;
            $referralCreditUsed = 0;

            // Apply loyalty points discount if available
            if ($user && $loyaltyPointsUsed > 0) {
                try {
                    // Make sure user has enough points
                    $availablePoints = $user->available_points;
                    $loyaltyPointsUsed = min($loyaltyPointsUsed, $availablePoints);

                    // Calculate points value
                    $pointsDiscount = $this->pointsService->pointsToDollars($loyaltyPointsUsed);

                    // Use the points from user account - TRACK ONLY IN USED_POINTS
                    if ($loyaltyPointsUsed > 0) {
                        // Load fresh user to avoid any model discrepancies
                        $freshUser = User::findOrFail($user->id);

                        // ONLY update used_points, do NOT decrease loyalty_points
                        $freshUser->used_points = $freshUser->used_points + $loyaltyPointsUsed;
                        $freshUser->save();

                        // Sync our user model
                        $user = $freshUser;

                        Log::info("Applied $loyaltyPointsUsed loyalty points worth $pointsDiscount to user $userId");
                    }
                } catch (Exception $e) {
                    Log::error("Failed to apply loyalty points: " . $e->getMessage());
                    // Continue without loyalty points if there's an error
                    $loyaltyPointsUsed = 0;
                    $pointsDiscount = 0;
                }
            }

            // Apply referral credit if requested
            if ($user && $useReferralCredit) {
                $availableReferralCredit = $this->referralService->getAvailableReferralBalance($user);

                if ($availableReferralCredit > 0) {
                    // Calculate amount to apply (up to remaining balance after points)
                    $remainingBalance = $total - $pointsDiscount;
                    $referralCreditUsed = min($availableReferralCredit, $remainingBalance);

                    // Use the referral credit
                    if ($referralCreditUsed > 0) {
                        $this->referralService->useReferralBalance($user, $referralCreditUsed, 'booking_discount');
                        Log::info("Applied $referralCreditUsed referral credit to user $userId");
                    }
                }
            }

            // 3. Calculate final values
            $discountAmount = $pointsDiscount + $referralCreditUsed;
            $finalTotal = max(0, $total - $discountAmount);
            $pointsEarned = (int)round($finalTotal);

            // 4. Create the booking record
            $booking = new Booking();
            $booking->user_id = $userId;
            $booking->activity_id = $activityId;
            $booking->booking_number = 'BK' . now()->format('YmdHis') . rand(1000, 9999);
            $booking->ticket_count = $quantity;
            $booking->total_price = $finalTotal;
            $booking->discount_amount = $discountAmount;
            $booking->loyalty_points_earned = $pointsEarned; // Store the points to be earned, but don't add to user yet
            $booking->loyalty_points_used = $loyaltyPointsUsed;
            $booking->status = 'pending';
            $booking->payment_status = 'paid';
            $booking->save();

            // 5. Create the pivot record in activity_booking
            $activityBooking = new ActivityBooking();
            $activityBooking->activity_id = $activityId;
            $activityBooking->booking_id = $booking->id;
            $activityBooking->save();

            // 6. Create participant records
            foreach ($participants as $participantData) {
                ActivityParticipant::create([
                    'booking_id' => $booking->id,
                    'activity_id' => $activityId,
                    'name' => $participantData['name'],
                    'email' => $participantData['email'] ?? null,
                    'phone' => $participantData['phone'] ?? null,
                ]);
            }

            // 7. Points are calculated and stored in booking, BUT NOT added to user account
            // They will be added only when an admin marks the booking as 'completed'
            if ($user && $pointsEarned > 0) {
                Log::info("Calculated $pointsEarned points for user $userId (booking {$booking->id}). Points will be added when booking is completed by admin.");
            }

            DB::commit();

            // Return success response
            return [
                'success' => true,
                'message' => 'Booking created successfully',
                'data' => [
                    'booking_id' => $booking->id,
                    'booking_number' => $booking->booking_number,
                    'loyalty_points_earned' => $pointsEarned,
                    'loyalty_points_used' => $loyaltyPointsUsed,
                    'points_discount_value' => $pointsDiscount,
                    'referral_credit_used' => $referralCreditUsed,
                    'final_total' => $finalTotal
                ]
            ];
        } catch (Exception $e) {
            DB::rollBack();

            // Log the error with detailed information
            Log::error('Booking creation error: ' . $e->getMessage());
            Log::error('Error file: ' . $e->getFile() . ' on line ' . $e->getLine());
            Log::error('Trace: ' . $e->getTraceAsString());

            // Return error response
            return [
                'success' => false,
                'error' => 'An error occurred while processing your booking. Please try again.',
                'debug_message' => $e->getMessage()
            ];
        }
    }
}
