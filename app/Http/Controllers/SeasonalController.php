<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\CategoryType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SeasonalController extends Controller
{
    /**
     * Display activities for a specific season
     */
    public function activities(Request $request, $season)
    {
        // Define season information
        $seasons = [
            'winter' => [
                'title' => 'Winter',
                'months' => 'December - February',
                'slug' => 'winter',
                'theme' => 'winter',
                'image' => 'api/placeholder/800/500',
                'banner' => 'api/placeholder/1920/600',
                'temperature' => 'Cold (0-10°C)',
                'weather' => 'Snow and Rain',
                'description' => 'Discover the magic of winter with activities designed for the cold season. From cozy indoor experiences to thrilling snow adventures, we offer a range of options to make your winter truly memorable.',
                'tips' => [
                    'Pack warm, waterproof clothing and insulated footwear',
                    'Consider visiting indoor attractions when weather is severe',
                    'Plan shorter daylight hours into your itinerary',
                    'Look for winter festivals and holiday celebrations',
                    'Book accommodations with heating and cozy amenities'
                ]
            ],
            'spring' => [
                'title' => 'Spring',
                'months' => 'March - May',
                'slug' => 'spring',
                'theme' => 'spring',
                'image' => 'api/placeholder/800/500',
                'banner' => 'api/placeholder/1920/600',
                'temperature' => 'Mild (10-20°C)',
                'weather' => 'Occasional Showers',
                'description' => 'Welcome the new season with our spring activities. Witness nature\'s rebirth with blooming gardens, refreshing hikes, and cultural experiences that celebrate renewal and growth.',
                'tips' => [
                    'Pack layers for varying temperatures throughout the day',
                    'Bring rain gear as spring showers are common',
                    'Visit gardens and natural areas for beautiful blooms',
                    'Be prepared for muddy trails if hiking',
                    'Look for seasonal food festivals and cultural events'
                ]
            ],
            'summer' => [
                'title' => 'Summer',
                'months' => 'June - August',
                'slug' => 'summer',
                'theme' => 'summer',
                'image' => 'api/placeholder/800/500',
                'banner' => 'api/placeholder/1920/600',
                'temperature' => 'Hot (25-35°C)',
                'weather' => 'Sunny and Clear',
                'description' => 'Make the most of summer\'s long days and warm weather with our specially curated activities. From beach escapes to mountain adventures, create unforgettable memories under the summer sun.',
                'tips' => [
                    'Pack lightweight, breathable clothing and sun protection',
                    'Stay hydrated and plan indoor breaks during peak heat',
                    'Book popular attractions in advance as summer is high season',
                    'Consider early morning activities to avoid crowds and heat',
                    'Look for outdoor concerts, festivals and evening events'
                ]
            ],
            'autumn' => [
                'title' => 'Autumn',
                'months' => 'September - November',
                'slug' => 'autumn',
                'theme' => 'autumn',
                'image' => 'api/placeholder/800/500',
                'banner' => 'api/placeholder/1920/600',
                'temperature' => 'Cool (10-20°C)',
                'weather' => 'Partly Cloudy',
                'description' => 'Experience the beauty of fall with our autumn activities. Enjoy the spectacular foliage, harvest festivals, and the perfect weather for outdoor adventures during this colorful season.',
                'tips' => [
                    'Pack layers for cool mornings and evenings',
                    'Bring a camera to capture the stunning fall colors',
                    'Visit orchards and farms for harvest activities',
                    'Check for seasonal wine tastings and food festivals',
                    'Book accommodations in advance for peak foliage season'
                ]
            ],
        ];

        // Validate that the requested season exists
        if (!isset($seasons[$season])) {
            abort(404, 'Season not found');
        }

        $seasonInfo = $seasons[$season];

        // Query activities by season
        $activitiesQuery = Activity::where('season', $season);

        // Apply type filter if specified
        if ($request->has('type') && $request->type) {
            $activitiesQuery->where('category_type_id', $request->type);
        }

        // Apply duration filter if specified
        if ($request->has('duration')) {
            switch ($request->duration) {
                case 'half_day':
                    $activitiesQuery->whereRaw('TIMESTAMPDIFF(HOUR, start_date, end_date) <= 4');
                    break;
                case 'full_day':
                    $activitiesQuery->whereRaw('TIMESTAMPDIFF(HOUR, start_date, end_date) > 4')
                        ->whereRaw('TIMESTAMPDIFF(HOUR, start_date, end_date) <= 8');
                    break;
                case 'multi_day':
                    $activitiesQuery->whereRaw('TIMESTAMPDIFF(HOUR, start_date, end_date) > 24');
                    break;
            }
        }

        // Apply price filter if specified
        if ($request->has('min_price')) {
            $activitiesQuery->where('price', '>=', $request->min_price);
        }
        if ($request->has('max_price')) {
            $activitiesQuery->where('price', '<=', $request->max_price);
        }

        // Apply sorting
        if ($request->has('sort_by')) {
            $sortDirection = $request->has('sort_dir') ? $request->sort_dir : 'asc';

            switch ($request->sort_by) {
                case 'price':
                    $activitiesQuery->orderBy('price', $sortDirection);
                    break;
                case 'popular':
                    $activitiesQuery->withCount('bookings')
                        ->orderBy('bookings_count', 'desc');
                    break;
                case 'rating':
                    $activitiesQuery->withCount(['reviews as average_rating' => function ($query) {
                        $query->select(DB::raw('coalesce(avg(rating),0)'));
                    }])
                        ->orderBy('average_rating', 'desc');
                    break;
                default:
                    $activitiesQuery->orderBy('name');
            }
        } else {
            // Default sorting: newest activities first
            $activitiesQuery->orderBy('created_at', 'desc');
        }

        // Get activity types for filter dropdowns
        $activityTypes = CategoryType::orderBy('name')->get();

        // Get price range for slider
        $priceRange = (object) [
            'min_price' => Activity::where('season', $season)->min('price') ?: 0,
            'max_price' => Activity::where('season', $season)->max('price') ?: 1000
        ];

        // Paginate results
        $activities = $activitiesQuery->with(['categoryType', 'reviews'])->paginate(9);

        return view('public.pages.seasonal_activities', [
            'season' => $seasonInfo,
            'activities' => $activities,
            'activityTypes' => $activityTypes,
            'priceRange' => $priceRange
        ]);
    }
}
