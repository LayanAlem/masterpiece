<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\ActivityImage;
use App\Models\CategoryType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

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
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_primary.*' => 'required|boolean',
        ]);

        // Start a database transaction
        DB::beginTransaction();

        try {
            // Create activity record
            $activity = new Activity();
            $activity->fill($validated);

            // Set boolean flags correctly
            $activity->is_family_friendly = $request->has('is_family_friendly');
            $activity->is_accessible = $request->has('is_accessible');

            // Set has_images flag if images are being uploaded
            $hasImages = $request->hasFile('images') && count(array_filter($request->file('images'))) > 0;
            $activity->has_images = $hasImages;

            $activity->save();

            // Process images if any are uploaded
            if ($hasImages) {
                $this->processActivityImages($request, $activity);
            }

            DB::commit();
            return redirect()->route('activities.index')->with('success', 'Activity created successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withInput()->with('error', 'Error creating activity: ' . $e->getMessage());
        }
    }

    public function show(Activity $activity)
    {
        // Load images relationship
        $activity->load('images');
        return view('admin.activities.show', compact('activity'));
    }

    public function edit(Activity $activity)
    {
        $categoryTypes = CategoryType::with('mainCategory')->orderBy('name')->get();
        // Load the activity's images
        $activity->load('images');
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
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_primary.*' => 'required|boolean',
            'delete_images.*' => 'nullable|exists:activity_images,id',
        ]);

        // Start a database transaction
        DB::beginTransaction();

        try {
            // Update activity record
            $activity->fill($validated);

            // Set boolean flags correctly
            $activity->is_family_friendly = $request->has('is_family_friendly');
            $activity->is_accessible = $request->has('is_accessible');

            // Handle images - Process deletions first
            if ($request->has('delete_images')) {
                foreach ($request->delete_images as $imageId) {
                    $image = ActivityImage::find($imageId);
                    if ($image) {
                        if (Storage::disk('public')->exists($image->path)) {
                            Storage::disk('public')->delete($image->path);
                        }
                        $image->delete();
                    }
                }
            }

            // Process new images
            $hasNewImages = $request->hasFile('images') && count(array_filter($request->file('images'))) > 0;

            if ($hasNewImages) {
                $this->processActivityImages($request, $activity);
            }

            // Update the has_images flag
            $activity->has_images = $activity->images()->count() > 0;
            $activity->save();

            DB::commit();
            return redirect()->route('activities.index')->with('success', 'Activity updated successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withInput()->with('error', 'Error updating activity: ' . $e->getMessage());
        }
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

        // Delete images
        $images = ActivityImage::where('activity_id', $activity->id)->get();
        foreach ($images as $image) {
            if (Storage::disk('public')->exists($image->path)) {
                Storage::disk('public')->delete($image->path);
            }
            $image->delete();
        }

        $activity->forceDelete();

        return redirect()->route('activities.trashed')
            ->with('success', 'Activity permanently deleted.');
    }

    /**
     * Process and store activity images
     */
    private function processActivityImages(Request $request, Activity $activity)
    {
        if ($request->hasFile('images')) {
            $images = $request->file('images');
            $isPrimary = $request->is_primary;

            foreach ($images as $key => $image) {
                // Skip if the file input is empty
                if (!$image) continue;

                // Store the image
                $path = $image->store('activities', 'public');

                // Create the image record
                ActivityImage::create([
                    'activity_id' => $activity->id,
                    'path' => $path,
                    'is_primary' => isset($isPrimary[$key]) ? (bool)$isPrimary[$key] : false,
                    'display_order' => $key
                ]);
            }
        }
    }
}
