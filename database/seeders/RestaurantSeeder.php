<?php

namespace Database\Seeders;

use App\Models\Restaurant;
use App\Models\FeaturedRestaurant;
use Illuminate\Database\Seeder;

class RestaurantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 15 random restaurants
        $restaurants = Restaurant::factory(15)->create();

        // Create specific popular restaurants
        $popularRestaurants = [
            [
                'name' => 'Sufra Restaurant',
                'description' => 'Located in one of Amman\'s oldest neighborhoods, Sufra offers traditional Jordanian and Levantine cuisine in a beautiful Ottoman-era house with a charming garden terrace.',
                'location' => 'Amman, Jordan',
                'cuisine_type' => 'Jordanian',
                'price_range' => '$$$',
                'is_active' => true,
            ],
            [
                'name' => 'Haret Jdoudna',
                'description' => 'Set in a beautifully restored old house in Madaba, this restaurant serves authentic Jordanian dishes in a historic setting with traditional decor and a welcoming atmosphere.',
                'location' => 'Madaba, Jordan',
                'cuisine_type' => 'Traditional Jordanian',
                'price_range' => '$$',
                'is_active' => true,
            ],
            [
                'name' => 'Captain\'s Restaurant',
                'description' => 'A seaside restaurant in Aqaba offering the freshest seafood with spectacular views of the Red Sea. Known for its grilled fish and Mediterranean-inspired dishes.',
                'location' => 'Aqaba, Jordan',
                'cuisine_type' => 'Seafood',
                'price_range' => '$$$',
                'is_active' => true,
            ],
        ];

        // Create each popular restaurant
        foreach ($popularRestaurants as $restaurantData) {
            $restaurant = Restaurant::create([
                'name' => $restaurantData['name'],
                'description' => $restaurantData['description'],
                'location' => $restaurantData['location'],
                'contact_number' => '+962' . rand(70, 79) . rand(1000000, 9999999),
                'email' => strtolower(str_replace(' ', '', $restaurantData['name'])) . '@example.com',
                'website' => 'https://www.' . strtolower(str_replace(' ', '', $restaurantData['name'])) . '.com',
                'image' => 'restaurants/' . strtolower(str_replace(' ', '-', $restaurantData['name'])) . '.jpg',
                'cuisine_type' => $restaurantData['cuisine_type'],
                'price_range' => $restaurantData['price_range'],
                'is_active' => $restaurantData['is_active'],
            ]);

            // Add to the restaurants collection for featured selection
            $restaurants->push($restaurant);
        }

        // Select 5 random restaurants to be featured
        $featuredRestaurants = $restaurants->random(5);

        foreach ($featuredRestaurants as $restaurant) {
            // Create a featured period for each selected restaurant
            $startDate = now()->subDays(rand(1, 10));
            $endDate = (clone $startDate)->addDays(rand(30, 90));

            FeaturedRestaurant::create([
                'restaurant_id' => $restaurant->id,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'is_active' => true,
                'display_order' => rand(1, 5),
                'payment_amount' => rand(100, 300),
            ]);
        }
    }
}
