<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Handle the search request
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function search(Request $request)
    {
        $query = $request->input('query');

        // Search activities
        $activities = Activity::where('name', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->orWhere('location', 'like', "%{$query}%")
            ->with(['categoryType.mainCategory', 'images'])
            ->limit(15)
            ->get();

        // Search restaurants
        $restaurants = Restaurant::where('name', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->orWhere('location', 'like', "%{$query}%")
            ->orWhere('cuisine_type', 'like', "%{$query}%")
            ->where('is_active', true)
            ->limit(10)
            ->get();

        return view('public.pages.search_results', [
            'activities' => $activities,
            'restaurants' => $restaurants,
            'query' => $query
        ]);
    }
}
