<?php

namespace Database\Seeders;

use App\Models\MainCategory;
use App\Models\CategoryType;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define main categories with descriptions
        $mainCategories = [
            [
                'name' => 'Trips',
                'description' => 'Explore Jordan\'s stunning landscapes and diverse attractions through various types of guided trips and experiences.',
                'image' => 'categories/trips.jpg',
            ],
            [
                'name' => 'Events',
                'description' => 'Join exciting events across Jordan, from musical performances to sports competitions, conferences, charity gatherings, and food festivals.',
                'image' => 'categories/events.jpg',
            ],
        ];

        // Create the main categories
        foreach ($mainCategories as $category) {
            $mainCategory = MainCategory::create($category);

            // Define category types for each main category
            $categoryTypes = [];

            switch ($mainCategory->name) {
                case 'Trips':
                    $categoryTypes = [
                        ['name' => 'Adventure', 'description' => 'Thrilling adventure trips including hiking, rock climbing, and desert experiences'],
                        ['name' => 'Cultural', 'description' => 'Immersive cultural experiences showcasing Jordan\'s rich heritage and traditions'],
                        ['name' => 'Relaxation', 'description' => 'Peaceful getaways and leisure trips to unwind and enjoy Jordan\'s serene environments'],
                        ['name' => 'Wellness', 'description' => 'Health-focused experiences including spa treatments and rejuvenation activities'],
                        ['name' => 'Historical', 'description' => 'Journeys through time exploring Jordan\'s ancient sites and archaeological treasures'],
                    ];
                    break;

                case 'Events':
                    $categoryTypes = [
                        ['name' => 'Musical', 'description' => 'Concerts, festivals, and performances showcasing local and international music'],
                        ['name' => 'Sports', 'description' => 'Sporting events, competitions, and recreational activities throughout Jordan'],
                        ['name' => 'Conferences', 'description' => 'Professional gatherings, seminars, and educational events'],
                        ['name' => 'Charity', 'description' => 'Fundraising events and community initiatives supporting various causes'],
                        ['name' => 'Food', 'description' => 'Culinary festivals, tastings, and food-related celebrations highlighting Jordanian cuisine'],
                    ];
                    break;
            }

            // Create the category types for this main category
            foreach ($categoryTypes as $type) {
                $type['main_category_id'] = $mainCategory->id;
                $type['image'] = 'category-types/' . strtolower(str_replace(' ', '-', $type['name'])) . '.jpg';
                CategoryType::create($type);
            }
        }
    }
}
