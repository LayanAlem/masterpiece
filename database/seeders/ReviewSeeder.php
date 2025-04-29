<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\Booking;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $completedBookings = Booking::where('status', 'completed')
            ->whereDoesntHave('review')
            ->with('activities') // Eager-load activities
            ->get();

        $reviewComments = [
            1 => [ "Disappointed with this experience. Not as advertised.", "Poor service and organization. Would not recommend.", "Not worth the money at all. Very basic offering." ],
            2 => [ "Below average experience. Some good moments but mostly disappointing.", "The guide was nice but the activity itself wasn't well planned.", "Overpriced for what you get. Expected more." ],
            3 => [ "Average experience. Nothing special but nothing terrible either.", "Decent activity but room for improvement in organization.", "Met expectations but didn't exceed them. Fair value." ],
            4 => [ "Great experience! Enjoyed most aspects of the activity.", "Very good guide and well-organized tour. Recommended!", "Beautiful scenery and good service. Would go again." ],
            5 => [ "Absolutely fantastic! Highlight of our trip to Jordan.", "Outstanding experience from start to finish. Couldn't ask for more!", "The guides were incredible and the views were breathtaking. Perfect day!", "Exceeded all our expectations. Truly memorable experience." ],
        ];

        $statuses = ['pending', 'approved', 'rejected'];

        foreach ($completedBookings as $booking) {
            if (rand(1, 100) > 70) continue;

            // ✅ use activities for many-to-many
            $activity = $booking->activities->first();
            if (!$activity) continue;

            $rating = $this->getWeightedRandomRating([
                1 => 5, 2 => 10, 3 => 20, 4 => 30, 5 => 35
            ]);

            $comment = $reviewComments[$rating][array_rand($reviewComments[$rating])];
            $status = $statuses[array_rand($statuses)];

            Review::create([
                'user_id'     => $booking->user_id,
                'activity_id' => $activity->id,
                'booking_id'  => $booking->id,
                'rating'      => $rating,
                'comment'     => $comment,
                'status'      => $status,
            ]);
        }

        $this->addReviewsForPopularActivities();
    }

    private function addReviewsForPopularActivities(): void
    {
        $activitiesNeedingReviews = Activity::withCount('reviews')
            ->having('reviews_count', '<', 3)
            ->get();

        $users = User::all();
        $statuses = ['pending', 'approved', 'rejected'];

        foreach ($activitiesNeedingReviews as $activity) {
            $reviewsToAdd = 3 - $activity->reviews_count;

            for ($i = 0; $i < $reviewsToAdd; $i++) {
                $availableUsers = $users->reject(fn($u) =>
                    Review::where('user_id', $u->id)
                        ->where('activity_id', $activity->id)
                        ->exists()
                );

                if ($availableUsers->isEmpty()) continue;

                $user = $availableUsers->random();

                $existingBooking = Booking::where('user_id', $user->id)
                    ->where('status', 'completed')
                    ->whereHas('activities', fn($query) =>
                        $query->where('activities.id', $activity->id)
                    )
                    ->first();

                if (!$existingBooking) continue;

                $rating = rand(1, 100) <= 80 ? rand(4, 5) : rand(1, 3);
                $status = $statuses[array_rand($statuses)];

                Review::create([
                    'user_id'     => $user->id,
                    'activity_id' => $activity->id,
                    'booking_id'  => $existingBooking->id,
                    'rating'      => $rating,
                    'comment'     => $this->generateComment($rating),
                    'status'      => $status,
                ]);
            }
        }
    }

    private function generateComment(int $rating): string
    {
        $positive = [ "Amazing experience! The guide was knowledgeable and friendly.", "Breathtaking views and well-organized tour. Highly recommend!", "One of the highlights of our trip to Jordan. Will do it again!", "Exceeded our expectations. The perfect activity for our family.", "Great value for money. The experience was authentic and memorable." ];
        $average  = [ "Decent experience overall. Some aspects could be improved.", "Nice activity but slightly overpriced for what you get.", "The guide was good but the itinerary felt rushed.", "Beautiful location but the logistics could be better.", "An okay experience. Nothing special but nothing bad either." ];
        $negative = [ "Disappointing experience. Not as described in the listing.", "Poor organization and the guide wasn't very helpful.", "Too expensive for what was offered. Would not recommend.", "The activity was rushed and we didn't get to enjoy it properly.", "Not worth the time or money. Look for alternatives." ];

        return match(true) {
            $rating >= 4 => $positive[array_rand($positive)],
            $rating == 3 => $average[array_rand($average)],
            default      => $negative[array_rand($negative)],
        };
    }

    private function getWeightedRandomRating(array $distribution): int
    {
        $sum  = array_sum($distribution);
        $rand = rand(1, $sum);
        $total = 0;

        foreach ($distribution as $rating => $weight) {
            $total += $weight;
            if ($rand <= $total) return $rating;
        }

        return 5;
    }
}
