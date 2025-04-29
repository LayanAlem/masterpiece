<?php

namespace Database\Factories;

use App\Models\Restaurant;
use Illuminate\Database\Eloquent\Factories\Factory;

class RestaurantFactory extends Factory
{
    protected $model = Restaurant::class;

    // Sample Jordanian cities
    protected $cities = ['Amman', 'Petra', 'Aqaba', 'Jerash', 'Madaba', 'Irbid', 'Salt', 'Ajloun', 'Wadi Rum'];

    // Sample restaurant names with Jordanian/Middle Eastern themes
    protected $restaurantNames = [
        'Sufra', 'Fakhr El-Din', 'Haret Jdoudna', 'Tannoureen', 'Hashem',
        'Beit Sitti', 'Kan Zamaan', 'Zarb', 'Um Qais', 'Romero',
        'Blue Fig', 'Wild Jordan', 'Reem Al Bawadi', 'Shams El Balad', 'Najla\'s'
    ];

    // Sample cuisine types
    protected $cuisineTypes = [
        'Jordanian', 'Lebanese', 'Mediterranean', 'Middle Eastern', 'International',
        'Fusion', 'Traditional Bedouin', 'Contemporary Arabic', 'Seafood', 'Vegetarian'
    ];

    public function definition(): array
    {
        $city = $this->faker->randomElement($this->cities);
        $name = $this->faker->unique()->randomElement($this->restaurantNames);
        $cuisineType = $this->faker->randomElement($this->cuisineTypes);
        $priceRanges = ['$', '$$', '$$$', '$$$$'];

        return [
            'name' => $name,
            'description' => $this->faker->paragraphs(2, true),
            'location' => $city . ', Jordan',
            'contact_number' => '+962' . $this->faker->numberBetween(70, 79) . $this->faker->randomNumber(7, true),
            'email' => $this->faker->safeEmail(),
            'website' => 'https://www.' . strtolower(str_replace(' ', '', $name)) . '.com',
            'image' => 'restaurants/' . strtolower(str_replace(' ', '-', $name)) . '.jpg',
            'cuisine_type' => $cuisineType,
            'price_range' => $this->faker->randomElement($priceRanges),
            'is_active' => $this->faker->boolean(90),
        ];
    }

    /**
     * Configure the restaurant as premium
     */
    public function premium(): static
    {
        return $this->state(fn (array $attributes) => [
            'price_range' => '$$$',
        ]);
    }

    /**
     * Configure the restaurant as budget-friendly
     */
    public function budget(): static
    {
        return $this->state(fn (array $attributes) => [
            'price_range' => '$',
        ]);
    }
}
