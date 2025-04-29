<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * Constructor to apply middleware
     */
    public function __construct()
    {
        $this->middleware(middleware: 'auth');
    }

    /**
     * Display the user's profile.
     */
    public function index()
    {
        $user = Auth::user();

        // Eager load bookings with their related activities and participants
        $user->load([
            'bookings' => function ($query) {
                $query->with(['activities.categoryType.mainCategory', 'participants']);
            },
            'wishlistedActivities.categoryType.mainCategory'
        ]);

        // Extract wishlisted activities from the user model for the view
        $wishlistedActivities = $user->wishlistedActivities;

        return view('public.pages.profile', compact('user', 'wishlistedActivities'));
    }

    /**
     * Update the user's profile information.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . Auth::id(),
            'phone' => 'nullable|string|max:20',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // dd($request->all());

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->phone = $request->phone;

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            try {
                // Validate the file is valid
                if (!$request->file('profile_image')->isValid()) {
                    return redirect()->back()
                        ->withInput()
                        ->withErrors(['profile_image' => 'The uploaded file is not valid.']);
                }

                // Delete old image if exists
                if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
                    Storage::disk('public')->delete($user->profile_image);
                }

                // Store new image with time prefix to prevent caching issues
                $fileName = time() . '_' . $request->file('profile_image')->getClientOriginalName();
                $imagePath = $request->file('profile_image')->storeAs('profile-images', $fileName, 'public');
                $user->profile_image = $imagePath;
            } catch (\Exception $e) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['profile_image' => 'Error uploading image: ' . $e->getMessage()]);
            }
        }

        $user->save();

        return redirect()->route('profile.index')->with('success', 'Profile updated successfully!');
    }

    /**
     * Update the user's password.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Check if current password matches
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect']);
        }

        // Update password
        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->route('profile.index')->with('success', 'Password updated successfully!');
    }
}
