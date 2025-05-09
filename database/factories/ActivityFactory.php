<?php

namespace Database\Factories;

use App\Models\CategoryType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Activity>
 */
class ActivityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = $this->faker->dateTimeBetween('+1 week', '+3 months');
        $endDate = $this->faker->dateTimeBetween($startDate, '+6 months');

        return [
            'category_type_id' => CategoryType::inRandomOrder()->first()->id,
            'name' => $this->faker->sentence(3),
            'description' => $this->faker->paragraphs(3, true),
            'price' => $this->faker->numberBetween(50, 300),
            'cost' => $this->faker->numberBetween(30, 150),
            'capacity' => $this->faker->numberBetween(5, 50),
            'location' => $this->faker->city() . ', Jordan',
            'start_date' => $startDate,
            'end_date' => $endDate,
            'season' => $this->faker->randomElement(['winter', 'spring', 'summer', 'autumn']),
            'is_family_friendly' => $this->faker->boolean(70),
            'is_accessible' => $this->faker->boolean(30),
            'has_images' => false, // Instead of image path, set has_images flag
        ];
    }
}
