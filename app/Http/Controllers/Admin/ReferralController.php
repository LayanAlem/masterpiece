<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ReferralController extends Controller
{
    /**
     * Display the referral program dashboard
     */
    public function index(Request $request)
    {
        // Initialize query
        $query = User::with('referrer')->withCount('referrals');

        // Apply search filter if provided
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('referral_code', 'like', "%{$search}%");
            });
        }

        // Apply filters
        if ($request->has('filter') && $request->filter != 'all') {
            switch ($request->filter) {
                case 'has_referred':
                    $query->has('referrals');
                    break;
                case 'was_referred':
                    $query->whereNotNull('referred_by');
                    break;
                case 'has_balance':
                    $query->where('referral_balance', '>', 0);
                    break;
            }
        }

        // Apply sorting
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'name_asc':
                    $query->orderBy('first_name', 'asc');
                    break;
                case 'name_desc':
                    $query->orderBy('first_name', 'desc');
                    break;
                case 'balance_asc':
                    $query->orderBy('referral_balance', 'asc');
                    break;
                case 'balance_desc':
                    $query->orderBy('referral_balance', 'desc');
                    break;
                case 'referrals_desc':
                    $query->withCount('referrals')->orderBy('referrals_count', 'desc');
                    break;
                default:
                    $query->orderBy('created_at', 'desc');
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // Get paginated results
        $users = $query->paginate(15)->withQueryString();

        // Get all users who have made successful referrals
        $referrers = User::has('referrals')->withCount('referrals')->get();

        // Calculate total referral rewards distributed
        $rewardAmount = (int) Setting::get('referral_reward_amount', 10);
        $totalRewardsDistributed = $referrers->sum('referrals_count') * $rewardAmount;

        // Get recent referrals with details
        $recentReferrals = User::whereNotNull('referred_by')
            ->with('referrer')
            ->latest()
            ->take(10)
            ->get();

        // Get monthly referral statistics for the chart
        $monthlyStats = $this->getMonthlyReferralStats();

        // Get referral program settings
        $settings = [
            'enabled' => Setting::get('referral_enabled', true),
            'reward_amount' => Setting::get('referral_reward_amount', 10),
            'max_uses' => Setting::get('referral_max_uses', 5),
        ];

        return view('admin.referrals.index', compact(
            'users',
            'referrers',
            'totalRewardsDistributed',
            'recentReferrals',
            'monthlyStats',
            'settings'
        ));
    }

    /**
     * Update referral program settings
     */
    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'referral_enabled' => 'required|boolean',
            'referral_reward_amount' => 'required|numeric|min:0',
            'referral_max_uses' => 'required|integer|min:1',
        ]);

        // Update settings
        Setting::set('referral_enabled', $validated['referral_enabled']);
        Setting::set('referral_reward_amount', $validated['referral_reward_amount']);
        Setting::set('referral_max_uses', $validated['referral_max_uses']);

        return redirect()
            ->route('referrals.index')
            ->with('success', 'Referral program settings updated successfully');
    }

    /**
     * Get monthly referral statistics for the past 6 months
     */
    private function getMonthlyReferralStats()
    {
        $stats = [];
        $startDate = Carbon::now()->subMonths(5)->startOfMonth();

        for ($i = 0; $i < 6; $i++) {
            $month = $startDate->copy()->addMonths($i);
            $nextMonth = $month->copy()->addMonth();

            $count = User::whereNotNull('referred_by')
                ->whereBetween('created_at', [$month, $nextMonth])
                ->count();

            $stats[] = [
                'month' => $month->format('M Y'),
                'count' => $count
            ];
        }

        return $stats;
    }
}
