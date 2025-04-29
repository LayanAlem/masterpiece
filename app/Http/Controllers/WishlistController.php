<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    /**
     * Toggle an activity in the user's wishlist (add or remove)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggle(Request $request)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'You must be logged in to add items to your wishlist'], 401);
        }

        // Validate request
        $request->validate([
            'activity_id' => 'required|exists:activities,id',
        ]);

        $userId = Auth::id();
        $activityId = $request->activity_id;

        // Check if item is already in wishlist
        $wishlistItem = Wishlist::where('user_id', $userId)
            ->where('activity_id', $activityId)
            ->first();

        if ($wishlistItem) {
            // Item exists, remove it
            $wishlistItem->delete();
            return response()->json([
                'success' => true,
                'message' => 'Activity removed from your wishlist',
                'is_added' => false
            ]);
        } else {
            // Item doesn't exist, add it
            Wishlist::create([
                'user_id' => $userId,
                'activity_id' => $activityId
            ]);
            return response()->json([
                'success' => true,
                'message' => 'Activity added to your wishlist',
                'is_added' => true
            ]);
        }
    }

    /**
     * Check if an activity is in the user's wishlist
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function check(Request $request)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return response()->json(['in_wishlist' => false]);
        }

        // Validate request
        $request->validate([
            'activity_id' => 'required|exists:activities,id',
        ]);

        $userId = Auth::id();
        $activityId = $request->activity_id;

        // Check if item is in wishlist
        $exists = Wishlist::where('user_id', $userId)
            ->where('activity_id', $activityId)
            ->exists();

        return response()->json(['in_wishlist' => $exists]);
    }

    /**
     * Remove an activity from the user's wishlist
     *
     * @param int $id - Activity ID to remove
     * @return \Illuminate\Http\RedirectResponse
     */
    public function remove($id)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $userId = Auth::id();

        // Find and delete the wishlist item
        Wishlist::where('user_id', $userId)
            ->where('activity_id', $id)
            ->delete();

        return redirect()->back()->with('success', 'Activity removed from your wishlist.');
    }
}
