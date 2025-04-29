<?php

namespace Database\Factories;

use App\Models\MainCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class MainCategoryFactory extends Factory
{
    protected $model = MainCategory::class;

    public function definition(): array
    {
        $categories = [
            'Adventure',
            'Cultural Tours',
            'Food & Drink',
            'Wellness',
            'Urban Experiences',
            'Historical Sites',
            'Nature & Wildlife',
            'Water Activities',
        ];

        $category = $this->faker->unique()->randomElement($categories);

        return [
            'name' => $category,
            'description' => $this->faker->paragraph(),
            'image' => 'categories/' . strtolower(str_replace(' ', '-', $category)) . '.jpg',
        ];
    }
}
