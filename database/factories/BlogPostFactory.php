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
        'Discovering Umm Qais'
    ];

    // Jordan locations
    protected $locations = [
        'Amman', 'Petra', 'Wadi Rum', 'Dead Sea', 'Aqaba',
        'Jerash', 'Madaba', 'Umm Qais', 'Ajloun', 'Salt',
        'Dana Biosphere Reserve', 'Wadi Mujib', 'Mount Nebo'
    ];

    public function definition(): array
    {
        $user = User::inRandomOrder()->first() ??
               User::factory()->create();

        $title = $this->faker->unique()->randomElement($this->blogTitles);
        $location = $this->faker->randomElement($this->locations);

        return [
            'user_id' => $user->id,
            'title' => $title,
            'content' => $this->faker->paragraphs(6, true),
            'location' => $location . ', Jordan',
            'image' => 'blog/' . strtolower(str_replace([' ', '\''], ['-', ''], $title)) . '.jpg',
            'vote_count' => $this->faker->numberBetween(0, 100),
            'is_approved' => $this->faker->boolean(80),
            'is_winner' => $this->faker->boolean(10),
        ];
    }

    /**
     * Configure the blog post as approved
     */
    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_approved' => true,
        ]);
    }

    /**
     * Configure the blog post as a contest winner
     */
    public function winner(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_approved' => true,
            'is_winner' => true,
            'vote_count' => $this->faker->numberBetween(50, 200),
        ]);
    }

    /**
     * Configure the blog post as pending approval
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_approved' => false,
            'is_winner' => false,
        ]);
    }
}
