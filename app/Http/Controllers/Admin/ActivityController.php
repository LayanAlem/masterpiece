<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\CategoryType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ActivityController extends Controller
{
    public function index(Request $request)
    {
        $query = Activity::with(['categoryType.mainCategory'])
            ->withCount('bookings');

        // Filter by category type
        if ($request->filled('category')) {
            $query->where('category_type_id', $request->category);
        }

        // Filter by date range
        if ($request->filled('date_range')) {
            switch ($request->date_range) {
                case 'past':
                    $query->where('end_date', '<', now());
                    break;
                case 'upcoming':
                    $query->where('start_date', '>', now());
                    break;
                case 'ongoing':
                    $query->where('start_date', '<=', now())
                        ->where('end_date', '>=', now());
                    break;
            }
        }

        // Filter by price range
        if ($request->filled('price_min') && $request->filled('price_max')) {
            $query->whereBetween('price', [$request->price_min, $request->price_max]);
        } else if ($request->filled('price_min')) {
            $query->where('price', '>=', $request->price_min);
        } else if ($request->filled('price_max')) {
            $query->where('price', '<=', $request->price_max);
        }

        // Search by name or location
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%");
            });
        }

        // Apply sorting (you can add this as a new feature)
        $sortField = $request->input('sort_by', 'created_at');
        $sortDirection = $request->input('sort_direction', 'desc');
        $query->orderBy($sortField, $sortDirection);

        // Replace get() with paginate()
        $activities = $query->paginate(10)->withQueryString();

        // Get all category types for the filter dropdown
        $categoryTypes = CategoryType::orderBy('name')->get();

        return view('admin.activities.index', compact('activities', 'categoryTypes'));
    }

    public function create()
    {
        $categoryTypes = CategoryType::with('mainCategory')->orderBy('name')->get();
        return view('admin.activities.create', compact('categoryTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_type_id' => 'required|exists:category_types,id',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'cost' => 'required|numeric|min:0',
            'capacity' => 'required|integer|min:1',
            'location' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'is_family_friendly' => 'sometimes|boolean',
            'is_accessible' => 'sometimes|boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);


        // Handle image upload - simplified to match MainCategoryController
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('activities', 'public');
        }

        Activity::create($validated);
        return redirect()->route('activities.index')->with('success', 'Activity created successfully.');
    }

    public function show(Activity $activity)
    {
        return view('admin.activities.show', compact('activity'));
    }

    public function edit(Activity $activity)
    {
        $categoryTypes = CategoryType::with('mainCategory')->orderBy('name')->get();
        return view('admin.activities.edit', compact('activity', 'categoryTypes'));
    }

    public function update(Request $request, Activity $activity)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_type_id' => 'required|exists:category_types,id',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'cost' => 'required|numeric|min:0',
            'capacity' => 'required|integer|min:1',
            'location' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'is_family_friendly' => 'sometimes|boolean',
            'is_accessible' => 'sometimes|boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Creating update data array
        // $activityData = [
        //     'name' => $validated['name'],
        //     'category_type_id' => $validated['category_type_id'],
        //     'description' => $validated['description'],
        //     'price' => $validated['price'],
        //     'cost' => $validated['cost'],
        //     'capacity' => $validated['capacity'],
        //     'location' => $validated['location'],
        //     'image' => $validated['image'],
        //     'start_date' => $validated['start_date'],
        //     'end_date' => $validated['end_date'],
        //     'is_family_friendly' => $request->has('is_family_friendly') ? 1 : 0,
        //     'is_accessible' => $request->has('is_accessible') ? 1 : 0,
        // ];

        // Handle image upload - updated to match MainCategoryController
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($activity->image && Storage::disk('public')->exists($activity->image)) {
                Storage::disk('public')->delete($activity->image);
            }

            // Store the new image
            $validated['image'] = $request->file('image')->store('activities', 'public');
        }

        $activity->update($validated);
        return redirect()->route('activities.index')->with('success', 'Activity updated successfully.');
    }

    public function destroy(Activity $activity)
    {
        $activity->delete();
        return redirect()->route('activities.index')->with('success', 'Activity deleted successfully.');
    }

    /**
     * Display a listing of the trashed activities.
     */
    public function trashed(Request $request)
    {
        $query = Activity::onlyTrashed()
            ->with(['categoryType.mainCategory'])
            ->withCount('bookings');

        // Add search/filter capabilities for trash if desired
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%");
            });
        }

        $activities = $query->latest()->paginate(10)->withQueryString();

        return view('admin.activities.trashed', compact('activities'));
    }

    /**
     * Restore the specified activity from trash.
     */
    public function restore($id)
    {
        $activity = Activity::onlyTrashed()->findOrFail($id);
        $activity->restore();

        return redirect()->route('activities.trashed')
            ->with('success', 'Activity restored successfully.');
    }

    /**
     * Permanently delete the specified activity from storage.
     */
    public function forceDelete($id)
    {
        $activity = Activity::onlyTrashed()->findOrFail($id);

        // Delete image
        if ($activity->image && Storage::disk('public')->exists($activity->image)) {
            Storage::disk('public')->delete($activity->image);
        }

        $activity->forceDelete();

        return redirect()->route('activities.trashed')
            ->with('success', 'Activity permanently deleted.');
    }
}
