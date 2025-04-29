<?php

namespace Database\Seeders;

use App\Models\BlogComment;
use App\Models\BlogPost;
use App\Models\BlogVote;
use App\Models\User;
use Illuminate\Database\Seeder;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create users if none exist
        if (User::count() == 0) {
            User::factory(10)->create();
        }

        $users = User::all();

        // Create 15 blog posts with different statuses
        // 10 approved, 3 pending, 2 winners
        BlogPost::factory(10)->approved()->create();
        BlogPost::factory(3)->pending()->create();
        BlogPost::factory(2)->winner()->create();

        // Get all blog posts for comments and votes
        $blogPosts = BlogPost::all();

        // Add comments to blog posts
        foreach ($blogPosts as $post) {
            // Skip comments for pending posts
            if (!$post->is_approved) {
                continue;
            }

            // Add 2-10 comments for each approved post
            $commentCount = rand(2, 10);

            for ($i = 0; $i < $commentCount; $i++) {
                // Random user for each comment
                $user = $users->random();

                BlogComment::create([
                    'blog_post_id' => $post->id,
                    'user_id' => $user->id,
                    'comment' => $this->generateRandomComment(),
                ]);
            }

            // Add votes to posts (if approved)
            // Number of votes should match the vote_count on the post
            $voterCount = $post->vote_count;

            // Get random users to be voters (without duplicates)
            $voters = $users->random(min($voterCount, $users->count()));

            foreach ($voters as $voter) {
                BlogVote::create([
                    'blog_post_id' => $post->id,
                    'user_id' => $voter->id,
                ]);
            }
        }
    }

    /**
     * Generate a random blog comment
     */
    private function generateRandomComment(): string
    {
        $comments = [
            "I visited this place last year and it was incredible. Your pictures really capture the beauty!",
            "Thanks for sharing these tips. I'm planning to visit Jordan next month and this is really helpful.",
            "The food looks amazing! Did you try the mansaf? It's my favorite Jordanian dish.",
            "Stunning photos! What camera did you use?",
            "I've always wanted to visit Petra. Is it really as magical as it looks in your photos?",
            "Great post! How many days would you recommend for exploring this area?",
            "The desert landscapes are absolutely breathtaking. Was it very hot when you visited?",
            "I love how you captured the local culture. It's so important to experience that when traveling.",
            "Did you feel safe traveling in Jordan? I'm considering a solo trip there.",
            "Your description of floating in the Dead Sea makes me want to experience it right away!",
            "The historical information you included really adds context to the beautiful photos.",
            "Were the locals friendly? I've heard Jordanians are very hospitable.",
            "This brings back so many memories of my trip to Jordan. Such a special place.",
            "Did you need a guide for this experience or did you explore on your own?",
            "The colors in these photos are incredible. Jordan looks like such a photogenic country!",
        ];

        return $comments[array_rand($comments)];
    }
}
