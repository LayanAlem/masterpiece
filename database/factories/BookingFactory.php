<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\Activity;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookingFactory extends Factory
{
    protected $model = Booking::class;

    // Keep track of used booking numbers to ensure uniqueness
    protected static $usedBookingNumbers = [];

    public function definition(): array
    {
        $user = User::inRandomOrder()->first() ??
               User::factory()->create();

        $ticketCount = $this->faker->numberBetween(1, 5);
        $basePrice = $this->faker->numberBetween(100, 500);
        $totalPrice = $ticketCount * $basePrice;
        $discountAmount = $this->faker->boolean(30) ?
                         $this->faker->numberBetween(10, 50) : 0;

        $loyaltyPointsEarned = intval(($totalPrice - $discountAmount) / 10);
        $loyaltyPointsUsed = $this->faker->boolean(20) ?
                            $this->faker->numberBetween(10, 100) : 0;

        // Booking statuses with weighted probability
        $statuses = [
            'pending' => 10,
            'confirmed' => 40,
            'completed' => 40,
            'cancelled' => 10
        ];

        $status = $this->getWeightedRandomElement($statuses);

        // Payment status based on booking status
        $paymentStatus = 'pending';
        if ($status === 'completed') {
            $paymentStatus = 'paid';
        } elseif ($status === 'confirmed') {
            $paymentStatus = $this->faker->randomElement(['pending', 'paid']);
        } elseif ($status === 'cancelled') {
            $paymentStatus = $this->faker->randomElement(['failed', 'refunded']);
        }

        // Generate a unique booking number
        $bookingNumber = $this->generateUniqueBookingNumber();
        $activity = Activity::inRandomOrder()->first(); // or create one


        return [
            'user_id' => $user->id,
            'activity_id' => $activity->id,
            'booking_number' => $bookingNumber,
            'ticket_count' => $ticketCount,
            'total_price' => $totalPrice,
            'discount_amount' => $discountAmount,
            'loyalty_points_earned' => $loyaltyPointsEarned,
            'loyalty_points_used' => $loyaltyPointsUsed,
            'status' => $status,
            'payment_status' => $paymentStatus,
        ];
    }

    /**
     * Generate a unique booking number that hasn't been used before
     */
    protected function generateUniqueBookingNumber(): string
    {
        $attempts = 0;
        $maxAttempts = 100;

        do {
            $randNum = $this->faker->unique()->randomNumber(4, true);
            $bookingNumber = 'JT-' . now()->format('Ymd') . '-' . $randNum;
            $attempts++;

            // Prevent infinite loops
            if ($attempts >= $maxAttempts) {
                $bookingNumber = 'JT-' . now()->format('Ymd') . '-' . uniqid();
                break;
            }
        } while (
            in_array($bookingNumber, self::$usedBookingNumbers) ||
            Booking::where('booking_number', $bookingNumber)->exists()
        );

        // Add to used list
        self::$usedBookingNumbers[] = $bookingNumber;

        return $bookingNumber;
    }

    /**
     * Configure the booking as completed with payment
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
            'payment_status' => 'paid',
        ]);
    }

    /**
     * Configure the booking as pending
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
            'payment_status' => 'pending',
        ]);
    }

    /**
     * Configure the booking as cancelled with refund
     */
    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'cancelled',
            'payment_status' => 'refunded',
        ]);
    }

    /**
     * Helper function to get a random element with weighted probabilities
     */
    private function getWeightedRandomElement(array $weightedValues)
    {
        $rand = mt_rand(1, array_sum($weightedValues));

        foreach ($weightedValues as $key => $value) {
            $rand -= $value;
            if ($rand <= 0) {
                return $key;
            }
        }
    }
}
