<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\ActivityParticipant;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookingParticipantFactory extends Factory
{
    protected $model = ActivityParticipant::class;

    public function definition(): array
    {
        $booking = Booking::inRandomOrder()->first() ??
            Booking::factory()->create();

        return [
            'booking_id' => $booking->id,
            'activity_id' => function () use ($booking) {
                // Assuming each booking is related to at least one activity
                return $booking->activities()->first()->id ?? 1;
            },
            'name' => $this->faker->name(),
            'email' => $this->faker->optional(0.7)->safeEmail(),
            'phone' => $this->faker->optional(0.5)->phoneNumber(),
        ];
    }
}
