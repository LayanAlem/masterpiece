<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Display a listing of the reviews.
     */
    public function index(Request $request)
    {
        $query = Review::with(['user', 'activity', 'booking']);

        // Apply status filter
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Apply rating filter
        if ($request->has('rating') && $request->rating !== 'all') {
            $query->where('rating', $request->rating);
        }

        // Apply date range filter
        if ($request->has('from_date') && $request->from_date) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->has('to_date') && $request->to_date) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        // Apply search filter
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($q) use ($search) {
                    $q->where('first_name', 'like', "%$search%")
                        ->orWhere('last_name', 'like', "%$search%");
                })
                    ->orWhereHas('activity', function ($q) use ($search) {
                        $q->where('name', 'like', "%$search%");
                    })
                    ->orWhere('comment', 'like', "%$search%");
            });
        }

        // Apply sorting
        $query->latest();

        // Preserve all current query parameters for pagination links
        $reviews = $query->paginate(10)->withQueryString();

        return view('admin.reviews.index', compact('reviews'));
    }

    /**
     * Display a listing of trashed reviews.
     */
    public function trashed()
    {
        $reviews = Review::with(['user', 'activity'])->onlyTrashed()->latest()->get();
        return view('admin.reviews.trashed', compact('reviews'));
    }

    /**
     * Restore a trashed review.
     */
    public function restore($id)
    {
        $review = Review::onlyTrashed()->findOrFail($id);
        $review->restore();
        return redirect()->route('reviews.trashed')
            ->with('success', 'Review restored successfully.');
    }

    /**
     * Permanently delete a review.
     */
    public function forceDelete($id)
    {
        $review = Review::onlyTrashed()->findOrFail($id);
        $review->forceDelete();

        redirect()->route('reviews.trashed')
            ->with('success', 'Review permanently deleted.');
    }

    /**
     * Show the form for creating a new review.
     */
    public function create()
    {
        // This might not be needed in admin panel, as reviews are typically created by users
        return redirect()->route('reviews.index');
    }

    /**
     * Display the specified review.
     */
    public function show(Review $review)
    {
        $review->load(['user', 'activity']);
        return view('admin.reviews.show', compact('review'));
    }

    /**
     * Show the form for editing the specified review.
     */
    public function edit(Review $review)
    {
        $review->load(['user', 'activity']);
        return view('admin.reviews.edit', compact('review'));
    }

    /**
     * Update the specified review in storage.
     */
    public function update(Request $request, Review $review)
    {
        $validated = $request->validate([
            'comment' => 'nullable|string|max:1000',
            'rating' => 'nullable|integer|min:1|max:5',
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $review->update($validated);

        return redirect()->route('reviews.index')
            ->with('success', 'Review has been updated successfully.');
    }

    /**
     * Remove the specified review from storage.
     */
    public function destroy(Review $review)
    {
        $review->delete();

        return redirect()->route('reviews.index')
            ->with('success', 'Review has been moved to trash.');
    }

    /**
     * Export reviews to CSV.
     */
    public function export()
    {
        $reviews = Review::with(['user', 'activity'])->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="reviews.csv"',
        ];

        $callback = function () use ($reviews) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'User', 'Activity', 'Rating', 'Comment', 'Status', 'Created At']);

            foreach ($reviews as $review) {
                fputcsv($file, [
                    $review->id,
                    $review->user ? ($review->user->first_name . ' ' . $review->user->last_name) : 'N/A',
                    $review->activity ? $review->activity->name : 'N/A',
                    $review->rating,
                    $review->comment,
                    $review->status,
                    $review->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
