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
    public function index()
    {
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
            ->route('admin.referrals.index')
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
