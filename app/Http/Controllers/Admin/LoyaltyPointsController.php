<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\PointsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoyaltyPointsController extends Controller
{
    protected $pointsService;

    public function __construct(PointsService $pointsService)
    {
        $this->pointsService = $pointsService;
    }

    /**
     * Display the loyalty points dashboard
     */
    public function index()
    {
        // Get summary statistics
        $totalUsers = User::count();
        $totalPoints = User::sum('loyalty_points');
        $totalUsedPoints = User::sum('used_points');
        $avgPointsPerUser = $totalUsers > 0 ? round($totalPoints / $totalUsers, 2) : 0;

        // Get top 10 users with most points
        $topUsers = User::orderBy('loyalty_points', 'desc')
            ->take(10)
            ->get();

        // Get recent point transactions (would need a points_transactions table in a real implementation)
        // For now, we'll just get recently updated users as a proxy
        $recentActivity = User::whereNotNull('loyalty_points')
            ->where('loyalty_points', '>', 0)
            ->orderBy('updated_at', 'desc')
            ->take(10)
            ->get();

        return view('admin.loyalty-points.index', compact(
            'totalUsers',
            'totalPoints',
            'totalUsedPoints',
            'avgPointsPerUser',
            'topUsers',
            'recentActivity'
        ));
    }

    /**
     * Show the loyalty points settings
     */
    public function settings()
    {
        // In a real system, you would fetch settings from database
        // For this example, we'll use the values from PointsService
        $dollarsToPointsRate = 1; // 1 point per $1
        $pointsToDollarsRate = 0.1; // $0.1 per point (10 points = $1)

        return view('admin.loyalty-points.settings', compact(
            'dollarsToPointsRate',
            'pointsToDollarsRate'
        ));
    }

    /**
     * Update loyalty points settings
     */
    public function updateSettings(Request $request)
    {
        // In a real system, you would save settings to database
        // and update the conversion rates in the PointsService

        $request->validate([
            'dollars_to_points_rate' => 'required|numeric|min:0.01',
            'points_to_dollars_rate' => 'required|numeric|min:0.01',
        ]);

        // Flash success message
        return redirect()->route('loyalty-points.settings')
            ->with('success', 'Loyalty points settings updated successfully');
    }

    /**
     * Show all users with their loyalty points
     */
    public function users(Request $request)
    {
        $query = User::query();

        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$search}%"]);
            });
        }

        // Apply min points filter
        if ($request->filled('min_points')) {
            $query->where('loyalty_points', '>=', $request->min_points);
        }

        // Apply max points filter
        if ($request->filled('max_points')) {
            $query->where('loyalty_points', '<=', $request->max_points);
        }

        $users = $query->orderBy('loyalty_points', 'desc')->paginate(15)->withQueryString();

        return view('admin.loyalty-points.users', compact('users'));
    }

    /**
     * Show form to adjust points for a specific user
     */
    public function editUserPoints($id)
    {
        $user = User::findOrFail($id);
        return view('admin.loyalty-points.edit-user', compact('user'));
    }

    /**
     * Adjust points for a specific user
     */
    public function updateUserPoints(Request $request, $id)
    {
        $request->validate([
            'action' => 'required|in:add,deduct',
            'points' => 'required|integer|min:1',
            'reason' => 'required|string|max:255',
        ]);

        $user = User::findOrFail($id);
        $action = $request->input('action');
        $points = (int) $request->input('points');
        $reason = $request->input('reason');

        if ($action === 'add') {
            $success = $this->pointsService->addPoints($user, $points, $reason);
        } else {
            $success = $this->pointsService->usePoints($user, $points, $reason);
        }

        if ($success) {
            return redirect()->route('loyalty-points.users')
                ->with('success', "Successfully {$action}ed {$points} points for {$user->name}");
        } else {
            return redirect()->route('loyalty-points.edit-user', $id)
                ->with('error', "Failed to {$action} points. Please check if the user has enough points.");
        }
    }
}
