<?php

namespace Database\Factories;

use App\Models\Activity;
use App\Models\Booking;
use App\Models\ActivityBooking;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class ActivityBookingFactory extends Factory
{
    protected $model = ActivityBooking::class;

    public function definition(): array
    {
        $booking = Booking::inRandomOrder()->first() ?? Booking::factory()->create();
        $activity = Activity::inRandomOrder()->first() ?? Activity::factory()->create();

        $quantity = $this->faker->numberBetween(1, 5);
        $unitPrice = $activity->price;
        $subtotal = $quantity * $unitPrice;

        $startDate = is_object($activity->start_date) ? $activity->start_date : Carbon::parse($activity->start_date);
        $endDate = is_object($activity->end_date) ? $activity->end_date : Carbon::parse($activity->end_date);

        if (!$startDate || !$endDate || $startDate >= $endDate) {
            $startDate = Carbon::now();
            $endDate = Carbon::now()->addDays(30);
        }

        $maxEndDate = Carbon::now()->addMonths(2);
        $maxDate = $endDate->lt($maxEndDate) ? $endDate : $maxEndDate;

        if ($maxDate->lte($startDate)) {
            $maxDate = $startDate->copy()->addDay();
        }

        $activityDateObj = $this->faker->dateTimeBetween(
            $startDate->toDateTimeString(),
            $maxDate->toDateTimeString()
        );

        $activityDate = $activityDateObj->format('Y-m-d');

        $activityTime = null;
        if ($this->faker->boolean(70)) {
            $hours = [9, 10, 11, 12, 13, 14, 15, 16];
            $minutes = [0, 15, 30, 45];
            $activityTime = sprintf('%02d:%02d:00',
                $this->faker->randomElement($hours),
                $this->faker->randomElement($minutes)
            );
        }

        return [
            'booking_id' => $booking->id,
            'activity_id' => $activity->id,
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'subtotal' => $subtotal,
            'activity_date' => $activityDate,
            'activity_time' => $activityTime,
        ];
    }
}
