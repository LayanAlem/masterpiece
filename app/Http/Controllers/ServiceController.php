<?php

namespace App\Http\Controllers;

use App\Models\MainCategory;
use App\Models\CategoryType;
use App\Models\Activity;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Display the services page with main categories.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get all main categories
        $mainCategories = MainCategory::with('categoryTypes')->get();

        // Define seasonal recommendation data with images
        $seasonalRecommendations = [
            [
                'title' => 'Winter',
                'months' => 'December - February',
                'image' => 'mainStyle/images/seasons/winter.jpg',
                'alt' => 'Winter Wonders',
                'slug' => 'winter'
            ],
            [
                'title' => 'Spring',
                'months' => 'March - May',
                'image' => 'mainStyle/images/seasons/spring.jpg',
                'alt' => 'Spring Adventures',
                'slug' => 'spring'
            ],
            [
                'title' => 'Summer',
                'months' => 'June - August',
                'image' => 'mainStyle/images/seasons/summer.jpg',
                'alt' => 'Summer Escapes',
                'slug' => 'summer'
            ],
            [
                'title' => 'Autumn',
                'months' => 'September - November',
                'image' => 'mainStyle/images/seasons/autumn.jpg',
                'alt' => 'Autumn Discoveries',
                'slug' => 'autumn'
            ]
        ];

        return view('public.pages.services', compact('mainCategories', 'seasonalRecommendations'));
    }

    /**
     * Display activities for a specific category.
     *
     * @param Request $request
     * @param int $categoryId
     * @return \Illuminate\View\View
     */
    public function categoryActivities(Request $request, $categoryId)
    {
        // Get the main category with its types
        $category = MainCategory::with('categoryTypes')->findOrFail($categoryId);

        // Get all category type IDs belonging to this category
        $categoryTypeIds = $category->categoryTypes->pluck('id')->toArray();

        // Start building the query
        $query = Activity::whereIn('category_type_id', $categoryTypeIds)
            ->with('categoryType');  // Eager load the category type

        // Apply search filter if provided
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = '%' . $request->search . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', $searchTerm)
                    ->orWhere('description', 'like', $searchTerm)
                    ->orWhere('location', 'like', $searchTerm);
            });
        }

        // Apply category type filter if provided
        if ($request->has('type') && !empty($request->type)) {
            $query->where('category_type_id', $request->type);
        }

        // Apply date filter
        if ($request->has('date_filter')) {
            switch ($request->date_filter) {
                case 'upcoming':
                    $query->upcoming(); // Using the scope defined in the Activity model
                    break;
                case 'this_week':
                    $query->where('start_date', '>=', now())
                        ->where('start_date', '<=', now()->addDays(7));
                    break;
                case 'this_month':
                    $query->where('start_date', '>=', now())
                        ->where('start_date', '<=', now()->addMonth());
                    break;
                case 'three_months':
                    $query->where('start_date', '>=', now())
                        ->where('start_date', '<=', now()->addMonths(3));
                    break;
                default:
                    $query->where('start_date', '>=', now()); // Default: only upcoming activities
            }
        } else {
            $query->where('start_date', '>=', now()); // Default: only upcoming activities
        }

        // Apply price filter
        if ($request->has('min_price') && $request->min_price != null) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->has('max_price') && $request->max_price != null) {
            $query->where('price', '<=', $request->max_price);
        }

        // Apply sorting
        if ($request->has('sort_by')) {
            $sortField = $request->sort_by;
            $sortDirection = $request->has('sort_dir') ? $request->sort_dir : 'asc';

            if ($sortField == 'start_date') {
                $query->orderBy('start_date', $sortDirection);
            } elseif ($sortField == 'price') {
                $query->orderBy('price', $sortDirection);
            } elseif ($sortField == 'name') {
                $query->orderBy('name', $sortDirection);
            }
        } else {
            // Default sorting
            $query->orderBy('start_date', 'asc');
        }

        // Paginate the results
        $activities = $query->paginate(9)->withQueryString();

        // Get min and max prices for the price filter
        $priceRange = Activity::whereIn('category_type_id', $categoryTypeIds)
            ->selectRaw('MIN(price) as min_price, MAX(price) as max_price')
            ->first();

        return view('public.pages.category_activities', compact(
            'category',
            'activities',
            'priceRange'
        ));
    }
}
