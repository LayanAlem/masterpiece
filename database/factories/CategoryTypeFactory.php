<?php

namespace Database\Factories;

use App\Models\CategoryType;
use App\Models\MainCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryTypeFactory extends Factory
{
    protected $model = CategoryType::class;

    // Subcategories organized by main category
    protected $categoryTypes = [
        'Adventure' => ['Hiking', 'Rock Climbing', 'Zip-lining', 'Paragliding', 'Canyoning'],
        'Cultural Tours' => ['City Tours', 'Art Tours', 'Heritage Tours', 'Local Traditions', 'Museum Tours'],
        'Food & Drink' => ['Culinary Tours', 'Wine Tasting', 'Cooking Classes', 'Food Markets', 'Traditional Dining'],
        'Wellness' => ['Spa Packages', 'Yoga Retreats', 'Meditation', 'Hot Springs', 'Fitness Retreats'],
        'Urban Experiences' => ['City Walks', 'Nightlife Tours', 'Shopping Tours', 'Photography Tours', 'Urban Sports'],
        'Historical Sites' => ['Ancient Ruins', 'Castles & Palaces', 'Religious Sites', 'Archaeological Tours', 'War Memorials'],
        'Nature & Wildlife' => ['Safari', 'Bird Watching', 'National Parks', 'Botanical Gardens', 'Wildlife Sanctuaries'],
        'Water Activities' => ['Swimming', 'Snorkeling', 'Diving', 'Kayaking', 'Sailing'],
    ];

    public function definition(): array
    {
        $mainCategory = MainCategory::inRandomOrder()->first() ??
                        MainCategory::factory()->create();

        // Get the appropriate subcategories for this main category
        $relevantTypes = $this->categoryTypes[$mainCategory->name] ??
                        ['General', 'Specialty', 'Family-Friendly', 'Premium', 'Seasonal'];

        $name = $this->faker->unique(true)->randomElement($relevantTypes);

        return [
            'main_category_id' => $mainCategory->id,
            'name' => $name,
            'description' => $this->faker->paragraph(),
            'image' => 'category-types/' . strtolower(str_replace(' ', '-', $name)) . '.jpg',
        ];
    }
}
