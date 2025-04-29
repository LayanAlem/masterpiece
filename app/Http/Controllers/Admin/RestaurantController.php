<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RestaurantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Restaurant::withCount('featuredPeriods');

        // Filter for featured restaurants
        if ($request->has('featured') && $request->featured == 'true') {
            $query->whereHas('featuredPeriods', function ($q) {
                $now = now();
                $q->where('start_date', '<=', $now)
                    ->where('end_date', '>=', $now)
                    ->where('is_active', true);
            });
        }

        // Filter by cuisine type
        if ($request->filled('cuisine_type')) {
            $query->where('cuisine_type', $request->cuisine_type);
        }

        // Filter by price range
        if ($request->filled('price_range')) {
            $query->where('price_range', $request->price_range);
        }

        // Filter by status
        if ($request->filled('status')) {
            $isActive = $request->status === 'active';
            $query->where('is_active', $isActive);
        }

        // Search by name
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Get unique cuisine types for the filter dropdown
        $cuisineTypes = Restaurant::distinct()->pluck('cuisine_type')->toArray();

        // Paginate the results
        $restaurants = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.restaurants.index', compact('restaurants', 'cuisineTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.restaurants.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'contact_number' => 'required|string|max:15',
            'email' => 'required|email|max:255',
            'website' => 'nullable|url|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'cuisine_type' => 'required|string|max:255',
            'price_range' => 'required|string|max:50',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('restaurants', 'public');
        }

        // Set default value for is_active if not provided
        if (!isset($validated['is_active'])) {
            $validated['is_active'] = false;
        }

        Restaurant::create($validated);

        return redirect()->route('restaurants.index')
            ->with('success', 'Restaurant created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Restaurant $restaurant)
    {
        $restaurant->load('featuredPeriods');
        return view('admin.restaurants.show', compact('restaurant'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Restaurant $restaurant)
    {
        return view('admin.restaurants.edit', compact('restaurant'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Restaurant $restaurant)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'contact_number' => 'required|string|max:15',
            'email' => 'required|email|max:255',
            'website' => 'nullable|url|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'cuisine_type' => 'required|string|max:255',
            'price_range' => 'required|string|max:50',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($restaurant->image) {
                Storage::disk('public')->delete($restaurant->image);
            }
            $validated['image'] = $request->file('image')->store('restaurants', 'public');
        }

        // Set default value for is_active if not provided
        if (!isset($validated['is_active'])) {
            $validated['is_active'] = false;
        }

        $restaurant->update($validated);

        return redirect()->route('restaurants.index')
            ->with('success', 'Restaurant updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Restaurant $restaurant)
    {
        $restaurant->delete();
        return redirect()->route('restaurants.index')
            ->with('success', 'Restaurant deleted successfully.');
    }

    /**
     * Display a listing of the soft deleted resources.
     */
    public function trashed()
    {
        $trashedRestaurants = Restaurant::onlyTrashed()->get();
        return view('admin.restaurants.trashed', compact('trashedRestaurants'));
    }

    /**
     * Restore the specified soft deleted resource.
     */
    public function restore($id)
    {
        $restaurant = Restaurant::onlyTrashed()->findOrFail($id);
        $restaurant->restore();

        return redirect()->route('restaurants.trashed')
            ->with('success', 'Restaurant restored successfully.');
    }

    /**
     * Permanently delete the specified soft deleted resource.
     */
    public function forceDelete($id)
    {
        $restaurant = Restaurant::onlyTrashed()->findOrFail($id);

        // Delete image if exists
        if ($restaurant->image) {
            Storage::disk('public')->delete($restaurant->image);
        }

        $restaurant->forceDelete();

        return redirect()->route('restaurants.trashed')
            ->with('success', 'Restaurant permanently deleted.');
    }

    /**
     * Add restaurant to featured list
     */
    public function addToFeatured(Request $request)
    {
        $validated = $request->validate([
            'restaurant_id' => 'required|exists:restaurants,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'is_active' => 'boolean',
        ]);

        // Check if already featured
        $restaurant = Restaurant::findOrFail($validated['restaurant_id']);
        if ($restaurant->isCurrentlyFeatured()) {
            return redirect()->route('restaurants.index')
                ->with('error', 'This restaurant is already featured.');
        }

        // Set default value for is_active if not provided
        if (!isset($validated['is_active'])) {
            $validated['is_active'] = false;
        }

        // Create new featured record
        $restaurant->featuredPeriods()->create([
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'is_active' => $validated['is_active'],
        ]);

        return redirect()->route('restaurants.index')
            ->with('success', 'Restaurant added to featured list successfully.');
    }

    /**
     * Remove restaurant from featured list
     */
    public function removeFromFeatured(Request $request)
    {
        $validated = $request->validate([
            'restaurant_id' => 'required|exists:restaurants,id',
        ]);

        $restaurant = Restaurant::findOrFail($validated['restaurant_id']);

        // Find and delete current feature periods
        $restaurant->featuredPeriods()
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->where('is_active', true)
            ->update(['is_active' => false]);

        return redirect()->route('restaurants.index')
            ->with('success', 'Restaurant removed from featured list successfully.');
    }
}
