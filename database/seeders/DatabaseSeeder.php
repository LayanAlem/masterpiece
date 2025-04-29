<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Clear any existing data
        $this->command->info('Clearing existing data...');
        $this->truncateTables();

        $this->command->info('Seeding core data...');

        // 1. Seed admin users
        $this->call(AdminSeeder::class);

        // 2. Seed regular users
        $this->call(UserSeeder::class);

        // 3. Seed categories and their types
        $this->call(CategorySeeder::class);

        // 4. Seed activities
        $this->call(ActivitySeeder::class);

        // 5. Seed restaurants and featured restaurants
        $this->call(RestaurantSeeder::class);

        // 6. Seed bookings (creates empty bookings)
        $this->command->info('Seeding booking data...');
        $this->call([
            BookingSeeder::class,
            ActivityBookingSeeder::class,
        ]);

        // 7. Seed reviews for activities
        $this->call(ReviewSeeder::class);

        // 8. Seed blog content
        $this->command->info('Seeding content data...');
        $this->call(BlogSeeder::class);

        // 9. Seed wishlists
        $this->call(WishlistSeeder::class);


        $this->command->info('Database seeding completed successfully!');
    }

    /**
     * Truncate tables before seeding
     */
    private function truncateTables(): void
    {
        // Disable foreign key checks for truncating
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Core tables
        DB::table('admins')->truncate();
        DB::table('users')->truncate();
        DB::table('main_categories')->truncate();
        DB::table('category_types')->truncate();
        DB::table('activities')->truncate();
        DB::table('restaurants')->truncate();
        DB::table('featured_restaurants')->truncate();

        // Booking related
        DB::table('bookings')->truncate();
        DB::table('activity_booking')->truncate();
        DB::table('activity_participants')->truncate();
        DB::table('payments')->truncate();
        DB::table('reviews')->truncate();

        // User engagement
        DB::table('wishlists')->truncate();
        DB::table('blog_posts')->truncate();
        DB::table('blog_comments')->truncate();
        DB::table('blog_votes')->truncate();

        // System tables
        DB::table('settings')->truncate();
        DB::table('weather_caches')->truncate();

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
