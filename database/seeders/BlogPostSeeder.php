j,<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BlogPost;
// use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class BlogPostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // First clear existing blog posts to avoid unique constraint issues
        BlogPost::truncate();

        // Make sure the storage directory exists
        Storage::makeDirectory('public/blog_images');

        // Get sample images from the resources folder or use placeholders
        $sampleImagesPath = resource_path('seed-images/blog');
        $hasRealImages = File::exists($sampleImagesPath);

        // Create blog posts without unique constraints
        $publishedPosts = BlogPost::factory()->count(10)->published()->make();
        $pendingPosts = BlogPost::factory()->count(5)->pending()->make();
        $rejectedPosts = BlogPost::factory()->count(3)->rejected()->make();
        $winnerPosts = BlogPost::factory()->count(2)->winner()->make();

        $allPosts = collect()->merge($publishedPosts)
            ->merge($pendingPosts)
            ->merge($rejectedPosts)
            ->merge($winnerPosts);

        foreach ($allPosts as $post) {
            // Generate a unique filename
            $timestamp = time() + rand(1, 1000); // Add randomness to ensure uniqueness
            $filename = $timestamp . '_' . Str::slug($post->title) . '.jpg';
            $destinationPath = 'public/blog_images/' . $filename;

            // Try to copy a sample image or download a placeholder
            if ($hasRealImages && count(File::files($sampleImagesPath)) > 0) {
                // Use a random sample image from the folder
                $sampleImages = File::files($sampleImagesPath);
                $randomImage = $sampleImages[array_rand($sampleImages)];
                Storage::put($destinationPath, file_get_contents($randomImage));
            } else {
                // Download a placeholder image
                $placeholderUrl = 'https://picsum.photos/800/600?random=' . rand(1, 1000);
                try {
                    $imageContent = file_get_contents($placeholderUrl);
                    if ($imageContent !== false) {
                        Storage::put($destinationPath, $imageContent);
                    }
                } catch (\Exception $e) {
                    // If download fails, create a basic placeholder image
                    $imgWidth = 800;
                    $imgHeight = 600;
                    $image = imagecreatetruecolor($imgWidth, $imgHeight);
                    $backgroundColor = imagecolorallocate($image, rand(0, 255), rand(0, 255), rand(0, 255));
                    imagefill($image, 0, 0, $backgroundColor);

                    // Add some text
                    $textColor = imagecolorallocate($image, 255, 255, 255);
                    $text = 'Blog Image Placeholder';
                    imagestring($image, 5, $imgWidth / 2 - 100, $imgHeight / 2 - 10, $text, $textColor);

                    // Save the image
                    ob_start();
                    imagejpeg($image);
                    $imageContent = ob_get_clean();
                    Storage::put($destinationPath, $imageContent);
                    imagedestroy($image);
                }
            }

            // Update the image path in the post - use the web-accessible path format
            $post->image = '/storage/blog_images/' . $filename;
            $post->save();
        }
    }
}
