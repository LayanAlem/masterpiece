<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request): View
    {
        $query = User::query();

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('points_min')) {
            $query->where('loyalty_points', '>=', $request->points_min);
        }

        if ($request->filled('points_max')) {
            $query->where('loyalty_points', '<=', $request->points_max);
        }

        // Sort results
        $sortBy = $request->sort_by ?? 'created_at';
        $sortOrder = $request->sort_order ?? 'desc';
        $query->orderBy($sortBy, $sortOrder);

        // Paginate results
        $users = $query->paginate(10)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     *
     * @return \Illuminate\View\View
     */
    public function create(): View
    {
        // Get all users for referral dropdown
        $users = User::select('id', 'first_name', 'last_name')->get();
        return view('admin.users.create', compact('users'));
    }

    /**
     * Store a newly created user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'loyalty_points' => 'nullable|integer|min:0',
            'referred_by' => 'nullable|exists:users,id',
        ]);

        $user = new User();
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->password = Hash::make($request->password);
        $user->loyalty_points = $request->loyalty_points ?? 0;
        $user->referred_by = $request->referred_by;
        $user->referral_code = Str::random(10);
        $user->save();

        return redirect()->route('users.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * Display the specified user.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id): View
    {
        $user = User::findOrFail($id);
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id): View
    {
        $user = User::findOrFail($id);
        $users = User::where('id', '!=', $id)
            ->select('id', 'first_name', 'last_name')
            ->get();

        return view('admin.users.edit', compact('user', 'users'));
    }

    /**
     * Update the specified user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $user = User::findOrFail($id);

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
            'loyalty_points' => 'nullable|integer|min:0',
            'referred_by' => [
                'nullable',
                'exists:users,id',
                function ($attribute, $value, $fail) use ($id) {
                    if ($value == $id) {
                        $fail('A user cannot refer themselves.');
                    }
                },
            ],
        ]);

        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->phone = $request->phone;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->loyalty_points = $request->loyalty_points ?? $user->loyalty_points;
        $user->referred_by = $request->referred_by;
        $user->save();

        return redirect()->route('users.index')
            ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified user from storage (soft delete).
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id): RedirectResponse
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully.');
    }

    /**
     * Display a listing of the trashed users.
     *
     * @return \Illuminate\View\View
     */
    public function trashed(): View
    {
        $users = User::onlyTrashed()->paginate(10);
        return view('admin.users.trashed', compact('users'));
    }

    /**
     * Restore the specified trashed user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function restore($id): RedirectResponse
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->restore();

        return redirect()->route('users.trashed')
            ->with('success', 'User restored successfully.');
    }

    /**
     * Permanently delete the specified user from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function forceDelete($id): RedirectResponse
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->forceDelete();

        return redirect()->route('users.trashed')
            ->with('success', 'User permanently deleted.');
    }

    /**
     * Update user's loyalty points.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePoints(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'points' => 'required|integer',
            'operation' => 'required|in:add,subtract,set',
        ]);

        $user = User::findOrFail($id);
        $points = $request->points;
        $operation = $request->operation;

        switch ($operation) {
            case 'add':
                $user->loyalty_points += $points;
                $message = "Added {$points} points to {$user->first_name}'s account.";
                break;
            case 'subtract':
                $user->loyalty_points = max(0, $user->loyalty_points - $points);
                $message = "Subtracted {$points} points from {$user->first_name}'s account.";
                break;
            case 'set':
                $user->loyalty_points = max(0, $points);
                $message = "Set {$user->first_name}'s points to {$points}.";
                break;
        }

        $user->save();

        return redirect()->back()->with('success', $message);
    }

    /**
     * Generate a new referral code for the user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function regenerateReferralCode($id): RedirectResponse
    {
        $user = User::findOrFail($id);
        $user->referral_code = Str::random(10);
        $user->save();

        return redirect()->back()
            ->with('success', 'Referral code regenerated successfully.');
    }
}
