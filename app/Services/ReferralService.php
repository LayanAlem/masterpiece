<?php

namespace App\Services;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class ReferralService
{
    /**
     * The PointsService instance
     */
    protected $pointsService;

    /**
     * Create a new ReferralService instance
     */
    public function __construct(PointsService $pointsService = null)
    {
        $this->pointsService = $pointsService ?? new PointsService();
    }

    /**
     * Generate a unique referral code for a user
     *
     * @param User $user
     * @return string
     */
    public function generateReferralCode(User $user): string
    {
        $length = (int) Setting::get('referral_code_length', 8);
        $prefix = strtoupper(substr($user->first_name, 0, 2));

        // Generate a random code
        $code = $prefix . Str::upper(Str::random($length - 2));

        // Ensure code is unique
        while (User::where('referral_code', $code)->exists()) {
            $code = $prefix . Str::upper(Str::random($length - 2));
        }

        return $code;
    }

    /**
     * Validate if a referral code is valid
     *
     * @param string $code
     * @return bool
     */
    public function isValidReferralCode(string $code): bool
    {
        if (!Setting::get('referral_enabled', true)) {
            return false;
        }

        return User::where('referral_code', $code)->exists();
    }

    /**
     * Apply a referral code to a user
     *
     * @param User $user
     * @param string $code
     * @return bool
     */
    public function applyReferralCode(User $user, string $code): bool
    {
        // User can't use their own referral code
        if ($user->referral_code === $code) {
            return false;
        }

        // Code must be valid and referral system must be enabled
        if (!$this->isValidReferralCode($code)) {
            return false;
        }

        // Find the referrer
        $referrer = User::where('referral_code', $code)->first();

        // Check if referrer has reached maximum referrals
        $maxUses = (int) Setting::get('referral_max_uses', 5);
        if ($referrer->referrals()->count() >= $maxUses) {
            return false;
        }

        // Set the referred_by field and save
        $user->referred_by = $referrer->id;
        $user->save();

        // Award the reward to the referrer
        $this->awardReferralBalance($referrer);

        return true;
    }

    /**
     * Award $10 referral balance to a referrer when someone uses their code
     *
     * @param User $referrer
     * @return bool
     */
    public function awardReferralBalance(User $referrer): bool
    {
        // Get referral reward amount in dollars
        $rewardAmount = (float) Setting::get('referral_reward_amount', 10.00);

        try {
            // Add to referrer's balance
            $referrer->referral_balance += $rewardAmount;
            $referrer->save();

            // Log the transaction
            Log::info("Added ${rewardAmount} referral balance to user {$referrer->id} ({$referrer->email})");

            return true;
        } catch (\Exception $e) {
            Log::error("Failed to add referral balance: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Use referral balance for a booking discount
     *
     * @param User $user
     * @param float $amount
     * @param string $reason
     * @return bool
     */
    public function useReferralBalance(User $user, float $amount, string $reason = 'booking_discount'): bool
    {
        if ($amount <= 0) {
            return false;
        }

        // Ensure user has enough referral balance
        if ($user->referral_balance < $amount) {
            return false;
        }

        try {
            // Reduce user's referral balance
            $user->referral_balance -= $amount;
            $user->save();

            // Log the transaction
            Log::info("Used ${amount} referral balance from user {$user->id} ({$user->email}). Reason: {$reason}");

            return true;
        } catch (\Exception $e) {
            Log::error("Failed to use referral balance: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get available referral balance for a user
     *
     * @param User $user
     * @return float
     */
    public function getAvailableReferralBalance(User $user): float
    {
        return (float) $user->referral_balance;
    }

    /**
     * Check if a user can refer more people
     *
     * @param User $user
     * @return bool
     */
    public function canReferMoreUsers(User $user): bool
    {
        $maxReferrals = (int) Setting::get('referral_max_uses', 5);
        $currentReferrals = User::where('referred_by', $user->id)->count();

        return $currentReferrals < $maxReferrals;
    }

    /**
     * Get count of successful referrals by a user
     *
     * @param User $user
     * @return int
     */
    public function getReferralCount(User $user): int
    {
        return User::where('referred_by', $user->id)->count();
    }

    /**
     * Get referral tier/generation of a user
     *
     * @param User $user
     * @return int
     */
    public function getReferralTier(User $user): int
    {
        if (!$user->referred_by) {
            return 0; // Not referred
        }

        $tier = 1; // Direct referral
        $currentUser = $user;

        // Trace up the referral chain to find the tier
        while ($currentUser->referred_by) {
            $referrer = User::find($currentUser->referred_by);
            if (!$referrer) {
                break;
            }
            $currentUser = $referrer;
            $tier++;
        }

        return $tier;
    }

    /**
     * Get all referrers in the chain (upline)
     *
     * @param User $user
     * @return array
     */
    public function getReferralChain(User $user): array
    {
        $chain = [];
        $currentUser = $user;

        // Traverse up the referral chain
        while ($currentUser->referred_by) {
            $referrer = User::find($currentUser->referred_by);

            // If we can't find the referrer, break
            if (!$referrer) {
                break;
            }

            // Add to chain
            $chain[] = [
                'id' => $referrer->id,
                'name' => $referrer->name,
                'tier' => count($chain) + 1
            ];

            // Move up the chain
            $currentUser = $referrer;
        }

        return $chain;
    }

    /**
     * Check if the user can continue the referral chain (iterate to next tier)
     *
     * @param User $user
     * @return bool
     */
    public function canContinueReferralChain(User $user): bool
    {
        // System settings
        $maxTiers = (int) Setting::get('max_referral_tiers', 3);

        // Get current tier
        $currentTier = $this->getReferralTier($user);

        // Check if user has reached maximum tier level
        return $currentTier < $maxTiers;
    }

    /**
     * Get users referred by a user at a specific tier
     *
     * @param User $user
     * @param int $tier
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getReferralsByTier(User $user, int $tier = 1)
    {
        if ($tier === 1) {
            // Direct referrals
            return User::where('referred_by', $user->id)->get();
        }

        // Get users at deeper tiers
        $users = collect();
        $directReferrals = User::where('referred_by', $user->id)->get();

        if ($tier === 2) {
            // Second tier referrals
            foreach ($directReferrals as $referral) {
                $secondTier = User::where('referred_by', $referral->id)->get();
                $users = $users->merge($secondTier);
            }
            return $users;
        }

        // For deeper tiers, recursively fetch referrals
        foreach ($directReferrals as $referral) {
            $deeperTier = $this->getReferralsByTier($referral, $tier - 1);
            $users = $users->merge($deeperTier);
        }

        return $users;
    }

    /**
     * Calculate rewards based on referral tier
     *
     * @param int $tier
     * @return float
     */
    public function calculateTierReward(int $tier): float
    {
        // Define reward amounts per tier
        $baseReward = (float) Setting::get('referral_reward_amount', 10.00);

        // Rewards decrease with each tier
        $tierMultipliers = [
            1 => 1.0,    // 100% of base reward for direct referrals
            2 => 0.5,    // 50% for second tier
            3 => 0.25,   // 25% for third tier
            4 => 0.1,    // 10% for fourth tier
            5 => 0.05    // 5% for fifth tier
        ];

        // Default to 0 for tiers beyond 5
        $multiplier = $tierMultipliers[$tier] ?? 0;

        return $baseReward * $multiplier;
    }

    /**
     * Award tiered referral rewards to the entire chain of referrers
     *
     * @param User $newUser The newly registered user
     * @return bool
     */
    public function processTieredReferralRewards(User $newUser): bool
    {
        // If the user wasn't referred, there's nothing to do
        if (empty($newUser->referred_by)) {
            return false;
        }

        // System settings
        $baseReward = (float) Setting::get('referral_reward_amount', 10.00);

        // Get direct referrer only - keeping it simple
        $referrer = User::find($newUser->referred_by);

        if (!$referrer) {
            return false;
        }

        // Award the balance with the base reward
        $referrer->referral_balance += $baseReward;
        $referrer->save();

        // Log the transaction
        Log::info("Added {$baseReward} referral balance to user {$referrer->id}");

        return true;
    }

    /**
     * Award referral balance based on tier
     *
     * @param User $referrer
     * @param int $tier
     * @return bool
     */
    public function awardTieredReferralBalance(User $referrer, int $tier = 1): bool
    {
        // Get tier-specific reward amount
        $rewardAmount = $this->calculateTierReward($tier);

        if ($rewardAmount <= 0) {
            return false;
        }

        try {
            // Add to referrer's balance
            $referrer->referral_balance += $rewardAmount;
            $referrer->save();

            // Log the transaction
            Log::info("Added ${rewardAmount} tier-${tier} referral balance to user {$referrer->id} ({$referrer->email})");

            return true;
        } catch (\Exception $e) {
            Log::error("Failed to add tiered referral balance: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Process multi-level referral rewards when a new user signs up
     *
     * @param User $newUser
     * @return bool
     */
    public function processMultiLevelReferral(User $newUser): bool
    {
        if (!$newUser->referred_by) {
            return false;
        }

        $referrer = User::find($newUser->referred_by);

        if (!$referrer) {
            return false;
        }

        // Award the referral balance - keeping it simple
        $baseReward = (float) Setting::get('referral_reward_amount', 10.00);
        $referrer->referral_balance += $baseReward;
        $referrer->save();

        // Log the transaction
        Log::info("Added {$baseReward} referral balance to user {$referrer->id}");

        return true;
    }

    /**
     * Check if the referral system should continue to iterate to deeper tiers
     *
     * @param User|null $user Optional user to check specific user tier limits
     * @return bool
     */
    public function continueToIterate(User $user = null): bool
    {
        // Get the maximum number of tiers allowed in the system
        $maxTiers = (int) Setting::get('max_referral_tiers', 3);

        // Check if multi-level referrals are enabled
        $multiLevelEnabled = (bool) Setting::get('referral_multi_level_enabled', true);

        // If multi-level referrals are disabled or max tiers is 1, don't continue
        if (!$multiLevelEnabled || $maxTiers <= 1) {
            return false;
        }

        // If no user is provided, just check system settings
        if (!$user) {
            return true;
        }

        // Check if user has reached maximum tier level
        $currentTier = $this->getReferralTier($user);

        // Check if the user has enough referrals (must have at least 3 direct referrals)
        $minDirectReferrals = (int) Setting::get('min_referrals_to_continue', 3);
        $directReferralsCount = $user->referrals()->count();

        // User can continue to iterate if they haven't reached the maximum tier
        // AND they have at least the minimum required direct referrals
        return $currentTier < $maxTiers && $directReferralsCount >= $minDirectReferrals;
    }
}
