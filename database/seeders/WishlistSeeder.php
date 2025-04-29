<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Database\Seeder;

class WishlistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all users and activities
        $users = User::all();
        $activities = Activity::all();

        // Each user will wishlist 0-5 activities
        foreach ($users as $user) {
            // Random number of wishlists per user
            $wishlistCount = rand(0, 5);

            if ($wishlistCount > 0) {
                // Get random activities for this user
                $randomActivities = $activities->random($wishlistCount);

                foreach ($randomActivities as $activity) {
                    Wishlist::create([
                        'user_id' => $user->id,
                        'activity_id' => $activity->id,
                    ]);
                }
            }
        }
    }
}
