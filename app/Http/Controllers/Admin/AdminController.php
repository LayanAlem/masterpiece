<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Admin::query();

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Get admins with pagination
        $admins = $query->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        // Get roles for filter dropdown
        $roles = Admin::distinct()->pluck('role')->toArray();

        return view("admin.admins.index", compact('admins', 'roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Only super_admin can create other admins
        if (Auth::guard('admin')->user()->role !== 'super_admin') {
            return redirect()->route('admins.index')
                ->with('error', 'You do not have permission to create admins.');
        }

        return view('admin.admins.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Only super_admin can create other admins
        if (Auth::guard('admin')->user()->role !== 'super_admin') {
            return redirect()->route('admins.index')
                ->with('error', 'You do not have permission to create admins.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string|in:admin,super_admin',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        Admin::create($validated);

        return redirect()->route('admins.index')->with('success', 'Admin created successfully!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Admin $admin)
    {
        // Only super_admin can edit, or admins can edit themselves
        $currentAdmin = Auth::guard('admin')->user();
        if ($currentAdmin->role !== 'super_admin' && $currentAdmin->id !== $admin->id) {
            return redirect()->route('admins.index')
                ->with('error', 'You do not have permission to edit this admin.');
        }

        return view('admin.admins.edit', compact('admin'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Admin $admin)
    {
        // Only super_admin can update, or admins can update themselves
        $currentAdmin = Auth::guard('admin')->user();
        if ($currentAdmin->role !== 'super_admin' && $currentAdmin->id !== $admin->id) {
            return redirect()->route('admins.index')
                ->with('error', 'You do not have permission to update this admin.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email,' . $admin->id,
        ]);

        // Only super_admin can change roles
        if ($currentAdmin->role === 'super_admin') {
            $request->validate([
                'role' => 'required|string|in:admin,super_admin',
            ]);
            $validated['role'] = $request->role;
        }

        // Only update password if provided
        if ($request->filled('password')) {
            $request->validate([
                'password' => 'required|string|min:8|confirmed',
            ]);
            $validated['password'] = Hash::make($request->password);
        }

        $admin->update($validated);
        return redirect()->route('admins.index')->with('success', 'Admin updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Admin $admin)
    {
        // Only super_admin can delete admins
        if (Auth::guard('admin')->user()->role !== 'super_admin') {
            return redirect()->route('admins.index')
                ->with('error', 'You do not have permission to delete admins.');
        }

        // Prevent deleting yourself
        if ($admin->id === Auth::guard('admin')->id()) {
            return redirect()->route('admins.index')
                ->with('error', 'You cannot delete your own account.');
        }

        $admin->delete();
        return redirect()->route('admins.index')->with('success', 'Admin moved to trash successfully!');
    }

    /**
     * Display a listing of trashed resources.
     */
    public function trashed()
    {
        // Only super_admin can view trashed admins
        if (Auth::guard('admin')->user()->role !== 'super_admin') {
            return redirect()->route('admins.index')
                ->with('error', 'You do not have permission to view trashed admins.');
        }

        $admins = Admin::onlyTrashed()->paginate(10);
        return view('admin.admins.trashed', compact('admins'));
    }

    /**
     * Restore the specified resource from trash.
     */
    public function restore($id)
    {
        // Only super_admin can restore admins
        if (Auth::guard('admin')->user()->role !== 'super_admin') {
            return redirect()->route('admins.index')
                ->with('error', 'You do not have permission to restore admins.');
        }

        $admin = Admin::onlyTrashed()->findOrFail($id);
        $admin->restore();

        return redirect()->route('admins.trashed')
            ->with('success', 'Admin restored successfully!');
    }

    /**
     * Permanently delete the specified resource.
     */
    public function forceDelete($id)
    {
        // Only super_admin can permanently delete admins
        if (Auth::guard('admin')->user()->role !== 'super_admin') {
            return redirect()->route('admins.index')
                ->with('error', 'You do not have permission to permanently delete admins.');
        }

        $admin = Admin::onlyTrashed()->findOrFail($id);
        $admin->forceDelete();

        return redirect()->route('admins.trashed')
            ->with('success', 'Admin permanently deleted!');
    }
}
