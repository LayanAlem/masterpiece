<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create bookings with different statuses

        // 20 completed bookings
        Booking::factory(20)->completed()->create();

        // 10 confirmed bookings
        Booking::factory(10)->state([
            'status' => 'confirmed',
            'payment_status' => 'paid',
        ])->create();

        // 5 pending bookings
        Booking::factory(5)->pending()->create();

        // 3 cancelled bookings
        Booking::factory(3)->cancelled()->create();

        // Create some bookings for the test user if it exists
        $testUser = User::where('email', 'test@example.com')->first();

        if ($testUser) {
            // Create one of each status for the test user
            Booking::factory()->completed()->create([
                'user_id' => $testUser->id,
            ]);

            Booking::factory()->state([
                'status' => 'confirmed',
                'payment_status' => 'paid',
            ])->create([
                'user_id' => $testUser->id,
            ]);

            Booking::factory()->pending()->create([
                'user_id' => $testUser->id,
            ]);

            Booking::factory()->cancelled()->create([
                'user_id' => $testUser->id,
            ]);
        }
    }
}
