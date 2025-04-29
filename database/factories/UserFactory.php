<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make('password'), // Set a default password
            'profile_image' => null,
            'phone' => $this->faker->phoneNumber(),
            'loyalty_points' => $this->faker->numberBetween(0, 1000),
            'referral_code' => Str::random(8),
            'referred_by' => null,
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Create a user with a high number of loyalty points
     */
    public function loyal(): static
    {
        return $this->state(fn (array $attributes) => [
            'loyalty_points' => $this->faker->numberBetween(500, 2000),
        ]);
    }
}
