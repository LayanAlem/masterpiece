<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\CategoryType;
use Illuminate\Database\Seeder;

class ActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all category types to create activities for
        $categoryTypes = CategoryType::all();

        foreach ($categoryTypes as $categoryType) {
            // Create 2-5 activities for each category type
            $activityCount = rand(2, 5);

            // Define specific activities for certain category types
            $specificActivities = [];

            // Add specific activities for popular category types
            switch ($categoryType->name) {
                case 'Hiking':
                    $specificActivities = [
                        [
                            'name' => 'Wadi Rum Desert Trek',
                            'description' => 'Explore the stunning red desert landscapes of Wadi Rum, known as the Valley of the Moon. This guided trek takes you through dramatic sandstone mountains, narrow canyons, and ancient rock inscriptions. Experience the same landscapes that have inspired countless filmmakers and adventurers.',
                            'price' => 85.00,
                            'cost' => 55.00,
                            'capacity' => 15,
                            'location' => 'Wadi Rum, Jordan',
                            'is_family_friendly' => true,
                            'season' => 'spring',
                            'image' => 'activities/hiking-wadi-rum.jpg'
                        ],
                        [
                            'name' => 'Dana Biosphere Reserve Hike',
                            'description' => 'Hike through Jordan\'s largest nature reserve, featuring diverse landscapes from sandstone cliffs to desert plains. This guided expedition showcases the region\'s incredible biodiversity and spectacular views while learning about conservation efforts.',
                            'price' => 75.00,
                            'cost' => 45.00,
                            'capacity' => 12,
                            'location' => 'Dana, Jordan',
                            'is_family_friendly' => false,
                            'season' => 'autumn',
                            'image' => 'activities/hiking-dana.jpg'
                        ],
                    ];
                    break;

                case 'Archaeological Tours':
                    $specificActivities = [
                        [
                            'name' => 'Petra by Night Experience',
                            'description' => 'Experience the magic of Petra illuminated by over 1,500 candles. Walk through the Siq to the Treasury, where you\'ll enjoy traditional Bedouin music and storytelling under the stars in this unforgettable nighttime experience.',
                            'price' => 125.00,
                            'cost' => 80.00,
                            'capacity' => 30,
                            'location' => 'Petra, Jordan',
                            'is_family_friendly' => true,
                            'season' => 'winter',
                            'image' => 'activities/petra-night.jpg'
                        ],
                        [
                            'name' => 'Jerash Roman City Tour',
                            'description' => 'Explore one of the best-preserved Roman provincial cities in the world. This guided tour takes you through colonnaded streets, theaters, temples, and baths while explaining the fascinating history of this ancient metropolis.',
                            'price' => 60.00,
                            'cost' => 35.00,
                            'capacity' => 20,
                            'location' => 'Jerash, Jordan',
                            'is_family_friendly' => true,
                            'season' => 'spring',
                            'image' => 'activities/jerash-tour.jpg'
                        ],
                    ];
                    break;

                case 'Culinary Tours':
                    $specificActivities = [
                        [
                            'name' => 'Amman Street Food Safari',
                            'description' => 'Discover the authentic flavors of Jordan on this guided food tour through Amman\'s vibrant streets. Sample local delicacies like falafel, kunafa, and traditional Jordanian mansaf while learning about the culinary traditions of the region.',
                            'price' => 65.00,
                            'cost' => 40.00,
                            'capacity' => 10,
                            'location' => 'Amman, Jordan',
                            'is_family_friendly' => true,
                            'season' => 'summer',
                            'image' => 'activities/amman-food.jpg'
                        ],
                    ];
                    break;

                case 'Swimming':
                    $specificActivities = [
                        [
                            'name' => 'Dead Sea Floating Experience',
                            'description' => 'Float effortlessly in the mineral-rich waters of the Dead Sea, the lowest point on Earth. This guided experience includes transportation, beach access, mud treatments, and relaxation time at one of the world\'s most unique natural wonders.',
                            'price' => 70.00,
                            'cost' => 40.00,
                            'capacity' => 25,
                            'location' => 'Dead Sea, Jordan',
                            'is_family_friendly' => true,
                            'is_accessible' => true,
                            'season' => 'summer',
                            'image' => 'activities/dead-sea.jpg'
                        ],
                    ];
                    break;
            }

            // Create the specific activities for this category type if any
            foreach ($specificActivities as $activityData) {
                $startDate = now()->addDays(rand(5, 20));
                $endDate = (clone $startDate)->addMonths(rand(3, 6));

                Activity::create([
                    'category_type_id' => $categoryType->id,
                    'name' => $activityData['name'],
                    'description' => $activityData['description'],
                    'price' => $activityData['price'],
                    'cost' => $activityData['cost'],
                    'capacity' => $activityData['capacity'],
                    'location' => $activityData['location'],
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'season' => $activityData['season'],
                    'is_family_friendly' => $activityData['is_family_friendly'] ?? false,
                    'is_accessible' => $activityData['is_accessible'] ?? false,
                    'image' => $activityData['image'],
                ]);

                // Reduce the number of random activities to create
                $activityCount--;
            }

            // Create random activities for the remaining count
            Activity::factory($activityCount)->create([
                'category_type_id' => $categoryType->id,
            ]);
        }
    }
}
