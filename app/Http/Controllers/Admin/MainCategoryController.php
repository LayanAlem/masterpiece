<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MainCategory;
use Illuminate\Support\Facades\Storage;

class MainCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = MainCategory::withCount('categoryTypes');

        // Apply search filter
        if ($request->has('search') && !empty($request->search)) {
            $query->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        // Apply sub-categories filter
        if ($request->has('has_subs')) {
            if ($request->has_subs === 'with') {
                $query->has('categoryTypes');
            } else if ($request->has_subs === 'without') {
                $query->doesntHave('categoryTypes');
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

        // Paginate results
        $mainCategories = $query->paginate(10)->withQueryString();

        return view('admin.mainCategories.index', compact('mainCategories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.mainCategories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('main_categories', 'public');
        }


        MainCategory::create($validated);

        return redirect()->route('main-categories.index')->with('success', 'Category created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $mainCategory = MainCategory::withCount('categoryTypes')->findOrFail($id);

        return view('admin.mainCategories.show', compact('mainCategory'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MainCategory $mainCategory)
    {
        return view('admin.mainCategories.edit', compact('mainCategory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MainCategory $mainCategory)
    {
        // Validate the input data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle image upload if provided
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($mainCategory->image && Storage::disk('public')->exists($mainCategory->image)) {
                Storage::disk('public')->delete($mainCategory->image);
            }

            // Store the new image
            $validated['image'] = $request->file('image')->store('main-categories', 'public');
        }

        // Update the main category
        $mainCategory->update($validated);

        // Refresh the category types count
        // $mainCategory->refreshCategoryTypesCount();

        return redirect()
            ->route('main-categories.index')
            ->with('success', 'Main category updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MainCategory $mainCategory)
    {
        $mainCategory->delete();
        return redirect()->route('main-categories.index')->with('success', 'User deleted!');
    }

    public function trashed(Request $request)
    {
        $query = MainCategory::onlyTrashed()->withCount('categoryTypes');

        // Apply search filter
        if ($request->has('search') && !empty($request->search)) {
            $query->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('description', 'like', '%' . $request->search . '%');
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

        // Paginate results
        $trashedCategories = $query->paginate(10)->withQueryString();

        return view('admin.mainCategories.trashed', compact('trashedCategories'));
    }

    // Add this new method for emptying trash
    public function emptyTrash()
    {
        $trashedCategories = MainCategory::onlyTrashed()->get();

        foreach ($trashedCategories as $category) {
            // Delete the image file if exists
            if ($category->image && Storage::disk('public')->exists($category->image)) {
                Storage::disk('public')->delete($category->image);
            }

            // Permanently delete from database
            $category->forceDelete();
        }

        return redirect()->route('main-categories.trashed')
            ->with('success', 'Trash has been emptied successfully.');
    }

    public function restore($id)
    {
        $category = MainCategory::onlyTrashed()->findOrFail($id);
        $category->restore();

        return redirect()->route('main-categories.trashed')
            ->with('success', "Category '{$category->name}' has been restored successfully.");
    }

    public function forceDelete($id)
    {
        $category = MainCategory::onlyTrashed()->findOrFail($id);
        $name = $category->name;

        // If you have relationships that should be deleted along with the category
        // you might want to handle them here

        $category->forceDelete();

        return redirect()->route('main-categories.trashed')
            ->with('success', "Category '{$name}' has been permanently deleted.");
    }
}
