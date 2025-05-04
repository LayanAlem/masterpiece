<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Activity;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // This might be used for a user's own reviews page
        $user = Auth::user();
        $reviews = $user->reviews()->with(['activity'])->latest()->get();

        return view('public.pages.reviews.index', compact('reviews'));
    }

    /**
     * Show the form for creating a new review.
     */
    public function create(Request $request)
    {
        $bookingId = $request->input('booking_id');
        $activityId = $request->input('activity_id');

        if (!$bookingId || !$activityId) {
            return redirect()->back()->with('error', 'Invalid review request');
        }

        $booking = Booking::where('id', $bookingId)
            ->where('user_id', Auth::id())
            ->where('status', 'completed')
            ->first();

        if (!$booking) {
            return redirect()->back()->with('error', 'You can only review completed activities');
        }

        // Check if the user has already reviewed this activity for this booking
        $existingReview = Review::where('user_id', Auth::id())
            ->where('booking_id', $bookingId)
            ->where('activity_id', $activityId)
            ->first();

        if ($existingReview) {
            return redirect()->route('reviews.edit', $existingReview->id)
                ->with('info', 'You have already reviewed this activity. You can edit your review here.');
        }

        $activity = Activity::findOrFail($activityId);

        return view('public.pages.reviews.create', compact('activity', 'booking'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'activity_id' => 'required|exists:activities,id',
            'booking_id' => 'required|exists:bookings,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:1000',
        ]);

        // Verify the booking belongs to the user and is completed
        $booking = Booking::where('id', $request->booking_id)
            ->where('user_id', Auth::id())
            ->where('status', 'completed')
            ->first();

        if (!$booking) {
            return redirect()->back()->with('error', 'You can only review completed activities')->withInput();
        }

        // Check if user already has a review for this booking/activity
        $existingReview = Review::where('user_id', Auth::id())
            ->where('booking_id', $request->booking_id)
            ->where('activity_id', $request->activity_id)
            ->first();

        if ($existingReview) {
            return redirect()->route('reviews.edit', $existingReview->id)
                ->with('info', 'You have already reviewed this activity. You can edit your review here.');
        }

        $review = Review::create([
            'user_id' => Auth::id(),
            'activity_id' => $request->activity_id,
            'booking_id' => $request->booking_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'status' => 'pending', // Reviews start as pending until approved by admin
        ]);

        return redirect()->route('activity.detail', $request->activity_id)
            ->with('success', 'Thank you for your review! It has been submitted for approval.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Review $review)
    {
        // Ensure the user can only view their own reviews
        if ($review->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('public.pages.reviews.show', compact('review'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Review $review)
    {
        // Ensure the user can only edit their own reviews
        if ($review->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $activity = $review->activity;
        $booking = $review->booking;

        return view('public.pages.reviews.edit', compact('review', 'activity', 'booking'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Review $review)
    {
        // Ensure the user can only update their own reviews
        if ($review->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:1000',
        ]);

        $review->update([
            'rating' => $request->rating,
            'comment' => $request->comment,
            'status' => 'pending', // Reset to pending when edited
        ]);

        return redirect()->route('activity.detail', $review->activity_id)
            ->with('success', 'Your review has been updated and is awaiting approval.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Review $review)
    {
        // Ensure the user can only delete their own reviews
        if ($review->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $review->delete();

        return redirect()->route('profile.index', ['tab' => 'reviews'])
            ->with('success', 'Your review has been deleted.');
    }

    /**
     * Submit a review directly from the activity detail page.
     */
    public function quickReview(Request $request)
    {
        $request->validate([
            'activity_id' => 'required|exists:activities,id',
            'booking_id' => 'required|exists:bookings,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:1000',
        ]);

        // Same verification as store
        $booking = Booking::where('id', $request->booking_id)
            ->where('user_id', Auth::id())
            ->where('status', 'completed')
            ->first();

        if (!$booking) {
            return response()->json(['status' => 'error', 'message' => 'You can only review completed activities']);
        }

        // Check for existing review
        $existingReview = Review::where('user_id', Auth::id())
            ->where('booking_id', $request->booking_id)
            ->where('activity_id', $request->activity_id)
            ->first();

        if ($existingReview) {
            return response()->json(['status' => 'error', 'message' => 'You have already reviewed this activity']);
        }

        $review = Review::create([
            'user_id' => Auth::id(),
            'activity_id' => $request->activity_id,
            'booking_id' => $request->booking_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'status' => 'pending',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Thank you for your review! It has been submitted for approval.'
        ]);
    }
}
