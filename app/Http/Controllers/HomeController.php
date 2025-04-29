<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\MainCategory;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // If you want the home page to be public, remove this middleware
        // If you want it to require auth, keep it
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Find the Trips main category
        $tripsCategory = MainCategory::where('name', 'Trips')->first();

        // Get activities that belong to the Trips main category through category types
        $tripActivities = [];
        if ($tripsCategory) {
            $tripActivities = Activity::whereHas('categoryType', function ($query) use ($tripsCategory) {
                $query->where('main_category_id', $tripsCategory->id);
            })
                ->with('categoryType') // Eager load the category type relation
                ->inRandomOrder()->take(7)->get();
        }

        // Get featured restaurants
        $featuredRestaurants = Restaurant::active()
            ->featured()
            ->orderBy('name')
            ->get();

        return view('public.index', [
            'tripActivities' => $tripActivities,
            'featuredRestaurants' => $featuredRestaurants
        ]);
    }
}
