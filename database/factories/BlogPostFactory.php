<?php

namespace Database\Factories;

use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BlogPostFactory extends Factory
{
    protected $model = BlogPost::class;

    // Jordan-related blog post titles
    protected $blogTitles = [
        'My Adventure in Wadi Rum',
        'A Day by the Dead Sea',
        'Exploring Petra: The Rose City',
        'Jordanian Cuisine: A Food Journey',
        'The Ancient Ruins of Jerash',
        'Sunset in Aqaba',
        'Bedouin Culture Experience',
        'Floating in the Dead Sea',
        'Hiking Dana Biosphere Reserve',
        'Desert Camping Under the Stars',
        'Amman: A City of Contrasts',
        'Traditional Jordanian Wedding',
        'The Roman Theater Experience',
        'Jordan\'s Best Kept Secrets',
        'Discovering Umm Qais',
        'Historical Sites of Jordan',
        'Jordan Valley Landscapes',
        'Hidden Waterfalls of Jordan',
        'Mountain Trekking in Jordan',
        'Street Food in Amman'
    ];

    // Jordan locations
    protected $locations = [
        'Amman',
        'Petra',
        'Wadi Rum',
        'Dead Sea',
        'Aqaba',
        'Jerash',
        'Madaba',
        'Umm Qais',
        'Ajloun',
        'Salt',
        'Dana Biosphere Reserve',
        'Wadi Mujib',
        'Mount Nebo'
    ];

    public function definition(): array
    {
        $user = User::inRandomOrder()->first() ??
            User::factory()->create();

        // Add randomness to title to avoid unique constraint issues
        $title = $this->faker->randomElement($this->blogTitles) . ' #' . rand(1, 1000);
        $location = $this->faker->randomElement($this->locations);

        // Use the new status field with the appropriate values
        $statuses = ['published', 'pending', 'rejected'];
        $statusWeights = [80, 15, 5]; // 80% published, 15% pending, 5% rejected

        return [
            'user_id' => $user->id,
            'title' => $title,
            'content' => $this->faker->paragraphs(6, true),
            'location' => $location . ', Jordan',
            'image' => null, // We'll set this in the seeder
            'vote_count' => $this->faker->numberBetween(0, 100),
            'status' => $this->faker->randomElement($statuses, $statusWeights),
            'is_winner' => $this->faker->boolean(10),
        ];
    }

    /**
     * Configure the blog post as published
     */
    public function published(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'published',
        ]);
    }

    /**
     * Configure the blog post as a contest winner
     */
    public function winner(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'published',
            'is_winner' => true,
            'vote_count' => $this->faker->numberBetween(50, 200),
        ]);
    }

    /**
     * Configure the blog post as pending approval
     */
    public function pending(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'pending',
            'is_winner' => false,
        ]);
    }

    /**
     * Configure the blog post as rejected
     */
    public function rejected(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'rejected',
            'is_winner' => false,
        ]);
    }
}
