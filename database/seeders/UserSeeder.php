<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a test user for easy login during development
        User::create([
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'test@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'phone' => '+962791234567',
            'loyalty_points' => 100,
            'referral_code' => 'TESTUSER',
        ]);

        // Create 20 regular users
        User::factory(20)->create();

        // Create 5 loyal users with high loyalty points
        User::factory(5)->loyal()->create();

        // Create 5 unverified users
        User::factory(5)->unverified()->create();

        // Create users with referral relationships
        // First create 5 users who will be referrers
        $referrers = User::factory(5)->create();

        // Then create users referred by these referrers
        foreach ($referrers as $referrer) {
            // Each referrer refers 1-3 users
            $referredCount = rand(1, 3);

            User::factory($referredCount)->create([
                'referred_by' => $referrer->id,
            ]);
        }
    }
}
