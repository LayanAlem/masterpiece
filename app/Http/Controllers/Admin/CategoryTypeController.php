<?php

namespace App\Http\Controllers\Admin; // Change 'admin' to 'Admin'

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CategoryType;
use App\Models\MainCategory;
use Illuminate\Support\Facades\Storage;



class CategoryTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = CategoryType::with('mainCategory')->withCount('activities');

        // Apply search filter
        if ($request->has('search') && !empty($request->search)) {
            $query->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        // Apply main category filter
        if ($request->has('main_category') && !empty($request->main_category)) {
            $query->where('main_category_id', $request->main_category);
        }

        // Apply activities filter
        if ($request->has('has_activities')) {
            if ($request->has_activities === 'with') {
                $query->has('activities');
            } else if ($request->has_activities === 'without') {
                $query->doesntHave('activities');
            }
        }

        // Apply sorting
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'name_asc':
                    $query->orderBy('name', 'asc');
                    break;
                case 'name_desc':
                    $query->orderBy('name', 'desc');
                    break;
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'oldest':
                    $query->orderBy('created_at', 'asc');
                    break;
                default:
                    $query->orderBy('created_at', 'desc');
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // Get main categories for filter dropdown
        $mainCategories = MainCategory::all();

        // Paginate results
        $categoryTypes = $query->paginate(10)->withQueryString();

        return view('admin.categoryTypes.index', compact('categoryTypes', 'mainCategories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $mainCategories = MainCategory::all();
        return view('admin.categoryTypes.create', compact('mainCategories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:category_types',
            'main_category_id' => 'nullable|exists:main_categories,id',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $categoryType = new CategoryType();
        $categoryType->name = $validated['name'];
        $categoryType->main_category_id = $validated['main_category_id'] ?? null;
        $categoryType->description = $validated['description'] ?? null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('category-types', 'public');
            $categoryType->image = $imagePath;
        }

        $categoryType->save();

        return redirect()->route('category-types.index')
            ->with('success', 'Category type created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $categoryType = CategoryType::with(['mainCategory', 'activities'])
            ->findOrFail($id);

        return view('admin.categoryTypes.show', compact('categoryType'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $categoryType = CategoryType::findOrFail($id);
        $mainCategories = MainCategory::all();

        return view('admin.categoryTypes.edit', compact('categoryType', 'mainCategories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $categoryType = CategoryType::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:category_types,name,' . $id,
            'main_category_id' => 'nullable|exists:main_categories,id',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'delete_image' => 'nullable|boolean',
        ]);

        $categoryType->name = $validated['name'];
        $categoryType->main_category_id = $validated['main_category_id'] ?? null;
        $categoryType->description = $validated['description'] ?? null;

        // Handle image
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($categoryType->image) {
                Storage::disk('public')->delete($categoryType->image);
            }

            // Upload new image
            $imagePath = $request->file('image')->store('category-types', 'public');
            $categoryType->image = $imagePath;
        } elseif ($request->has('delete_image') && $request->delete_image) {
            // Delete image without replacing
            if ($categoryType->image) {
                Storage::disk('public')->delete($categoryType->image);
                $categoryType->image = null;
            }
        }

        $categoryType->save();

        return redirect()->route('category-types.show', $categoryType->id)
            ->with('success', 'Category type updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $categoryType = CategoryType::findOrFail($id);

        // Soft delete or permanent delete based on your setup
        // If you're using soft deletes
        $categoryType->delete();

        // If you want to permanently delete
        // if ($categoryType->image) {
        //     Storage::disk('public')->delete($categoryType->image);
        // }
        // $categoryType->forceDelete();

        return redirect()->route('category-types.index')
            ->with('success', 'Category type moved to trash successfully.');
    }

    public function trashed(Request $request)
    {
        $query = CategoryType::onlyTrashed()->with('mainCategory');

        // Apply search filter
        if ($request->has('search') && !empty($request->search)) {
            $query->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        // Apply main category filter
        if ($request->has('main_category') && !empty($request->main_category)) {
            $query->where('main_category_id', $request->main_category);
        }

        // Apply sorting
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'name_asc':
                    $query->orderBy('name', 'asc');
                    break;
                case 'name_desc':
                    $query->orderBy('name', 'desc');
                    break;
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'oldest':
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'deleted_newest':
                    $query->orderBy('deleted_at', 'desc');
                    break;
                case 'deleted_oldest':
                    $query->orderBy('deleted_at', 'asc');
                    break;
                default:
                    $query->orderBy('deleted_at', 'desc');
            }
        } else {
            $query->orderBy('deleted_at', 'desc');
        }

        // Get main categories for filter dropdown
        $mainCategories = MainCategory::all();

        // Paginate results
        $trashedTypes = $query->paginate(10)->withQueryString();

        return view('admin.categoryTypes.trashed', compact('trashedTypes', 'mainCategories'));
    }

    // Add this method for restoring trashed items
    public function restore($id)
    {
        $categoryType = CategoryType::onlyTrashed()->findOrFail($id);
        $categoryType->restore();

        return redirect()->route('category-types.trashed')
            ->with('success', "Category type '{$categoryType->name}' has been restored successfully.");
    }

    // Add this method for force deleting trashed items
    public function forceDelete($id)
    {
        $categoryType = CategoryType::onlyTrashed()->findOrFail($id);

        // Delete the image if exists
        if ($categoryType->image && Storage::disk('public')->exists($categoryType->image)) {
            Storage::disk('public')->delete($categoryType->image);
        }

        $name = $categoryType->name;
        $categoryType->forceDelete();

        return redirect()->route('category-types.trashed')
            ->with('success', "Category type '{$name}' has been permanently deleted.");
    }

    // Add this new method for emptying trash
    public function emptyTrash()
    {
        $trashedTypes = CategoryType::onlyTrashed()->get();

        foreach ($trashedTypes as $type) {
            // Delete the image file if exists
            if ($type->image && Storage::disk('public')->exists($type->image)) {
                Storage::disk('public')->delete($type->image);
            }

            // Permanently delete from database
            $type->forceDelete();
        }

        return redirect()->route('category-types.trashed')
            ->with('success', 'Trash has been emptied successfully.');
    }
}
