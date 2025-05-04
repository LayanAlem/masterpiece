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
        return redirect()->route('admin.reviews.trashed')
            ->with('success', 'Review restored successfully.');
    }

    /**
     * Permanently delete a review.
     */
    public function forceDelete($id)
    {
        $review = Review::onlyTrashed()->findOrFail($id);
        $review->forceDelete();

        return redirect()->route('admin.reviews.trashed')
            ->with('success', 'Review permanently deleted.');
    }

    /**
     * Show the form for creating a new review.
     */
    public function create()
    {
        // This might not be needed in admin panel, as reviews are typically created by users
        return redirect()->route('admin.reviews.index');
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
        // Log request data for debugging
        \Log::info('Review update request:', [
            'review_id' => $review->id,
            'request_data' => $request->all()
        ]);

        // Make status field not required if only updating comment or rating
        $isStatusOnly = $request->has('status') && !$request->has('comment') && !$request->has('rating');

        $rules = [
            'comment' => 'nullable|string|max:1000',
            'rating' => 'nullable|integer|min:1|max:5',
        ];

        // Only add status rule if it's provided
        if ($request->has('status')) {
            $rules['status'] = 'required|in:pending,approved,rejected';
        }

        $validated = $request->validate($rules);

        // Apply validated data or keep existing values
        $dataToUpdate = [];
        if (isset($validated['status'])) {
            $dataToUpdate['status'] = $validated['status'];
        }
        if (isset($validated['comment'])) {
            $dataToUpdate['comment'] = $validated['comment'];
        }
        if (isset($validated['rating'])) {
            $dataToUpdate['rating'] = $validated['rating'];
        }

        // Log the data that will be updated
        \Log::info('Updating review with data:', $dataToUpdate);

        $review->update($dataToUpdate);

        // Log the updated review
        \Log::info('Review updated successfully:', [
            'review_id' => $review->id,
            'status' => $review->status
        ]);

        return redirect()->route('admin.reviews.index')
            ->with('success', 'Review has been updated successfully.');
    }

    /**
     * Remove the specified review from storage.
     */
    public function destroy(Review $review)
    {
        $review->delete();

        return redirect()->route('admin.reviews.index')
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

    /**
     * Bulk update reviews status
     */
    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'reviews' => 'required|array',
            'reviews.*' => 'exists:reviews,id',
            'action' => 'required|in:approve,reject,delete',
        ]);

        $reviews = Review::whereIn('id', $request->reviews)->get();

        foreach ($reviews as $review) {
            if ($request->action === 'approve') {
                $review->update(['status' => 'approved']);
            } elseif ($request->action === 'reject') {
                $review->update(['status' => 'rejected']);
            } elseif ($request->action === 'delete') {
                $review->delete();
            }
        }

        $actionVerb = $request->action === 'delete' ? 'deleted' : $request->action . 'd';
        $count = count($request->reviews);

        return redirect()->route('admin.reviews.index')
            ->with('success', "{$count} " . ($count === 1 ? 'review' : 'reviews') . " {$actionVerb} successfully.");
    }

    /**
     * Update the status of a review directly (alternative method)
     */
    public function updateStatus(Request $request, $id)
    {
        $review = Review::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:pending,approved,rejected',
        ]);

        // Log the status update request
        \Log::info('Direct status update request:', [
            'review_id' => $id,
            'new_status' => $validated['status'],
            'old_status' => $review->status
        ]);

        $review->update(['status' => $validated['status']]);

        return redirect()->route('admin.reviews.index')
            ->with('success', 'Review status updated to ' . ucfirst($validated['status']));
    }
}
