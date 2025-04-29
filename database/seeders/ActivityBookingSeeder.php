<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\Booking;
use App\Models\ActivityParticipant;
use App\Models\Payment;
use Illuminate\Database\Seeder;

class ActivityBookingSeeder extends Seeder
{
    public function run(): void
    {
        $bookings = Booking::all();
        $activities = Activity::all();

        foreach ($bookings as $booking) {
            // Pick 1–3 random activities to attach
            $selectedActivities = $activities->random(rand(1, 3));

            // Attach activities to booking
            $booking->activities()->attach($selectedActivities->pluck('id')->toArray());

            // Calculate simulated ticket count and price
            $ticketCount = rand(1, 5) * $selectedActivities->count();
            $totalPrice = $selectedActivities->sum('price') * $ticketCount;

            // Update the booking with totals
            $booking->update([
                'ticket_count' => $ticketCount,
                'total_price' => $totalPrice,
            ]);

            // Create participants equal to ticket count
            ActivityParticipant::factory($ticketCount)->create([
                'booking_id' => $booking->id,
            ]);

            // Create payment based on status
            if (in_array($booking->payment_status, ['paid', 'refunded'])) {
                Payment::create([
                    'booking_id' => $booking->id,
                    'payment_method' => rand(0, 1) ? 'credit_card' : 'paypal',
                    'transaction_id' => 'TXN-' . strtoupper(substr(md5(rand()), 0, 10)),
                    'amount' => $totalPrice - $booking->discount_amount,
                    'currency' => 'JOD',
                    'status' => $booking->payment_status === 'paid' ? 'completed' : 'refunded',
                ]);
            } elseif ($booking->payment_status === 'failed') {
                Payment::create([
                    'booking_id' => $booking->id,
                    'payment_method' => rand(0, 1) ? 'credit_card' : 'paypal',
                    'transaction_id' => null,
                    'amount' => $totalPrice - $booking->discount_amount,
                    'currency' => 'JOD',
                    'status' => 'failed',
                ]);
            }
        }
    }
}
