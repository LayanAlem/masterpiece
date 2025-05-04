<?php

namespace App\Services;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Log;

class PointsService
{
    /**
     * Add loyalty points to a user
     *
     * @param User $user
     * @param int $points
     * @param string $reason
     * @return bool
     */
    public function addPoints(User $user, int $points, string $reason = 'manual_addition'): bool
    {
        if ($points <= 0) {
            return false;
        }

        try {
            $user->loyalty_points += $points;
            $user->save();

            // Log points transaction
            Log::info("Added {$points} points to user {$user->id} ({$user->email}). Reason: {$reason}");

            return true;
        } catch (Exception $e) {
            Log::error("Failed to add points: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Use loyalty points from a user
     *
     * @param User $user
     * @param int $points
     * @param string $reason
     * @return bool
     */
    public function usePoints(User $user, int $points, string $reason = 'redemption'): bool
    {
        if ($points <= 0) {
            return false;
        }

        // Check if user has enough available points
        $availablePoints = $user->available_points ?? ($user->loyalty_points - $user->used_points);

        if ($availablePoints < $points) {
            Log::warning("User {$user->id} ({$user->email}) attempted to use {$points} points but only has {$availablePoints} available.");
            return false;
        }

        try {
            // The key fix: DECREASE loyalty_points when points are used
            $user->loyalty_points -= $points;
            // Still track used_points for historical tracking
            $user->used_points += $points;
            $user->save();

            // Log points transaction
            Log::info("Used {$points} points from user {$user->id} ({$user->email}). New balance: {$user->loyalty_points}, Total used: {$user->used_points}. Reason: {$reason}");

            return true;
        } catch (Exception $e) {
            Log::error("Failed to use points: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get user's available points
     *
     * @param User $user
     * @return int
     */
    public function getAvailablePoints(User $user): int
    {
        return $user->available_points ?? ($user->loyalty_points - $user->used_points);
    }

    /**
     * Convert a dollar amount to points
     *
     * @param float $amount
     * @return int
     */
    public function dollarsToPoints(float $amount): int
    {
        // Convert $1 to 1 point (can be adjusted based on your conversion rate)
        return (int) $amount;
    }

    /**
     * Convert points to a dollar amount
     *
     * @param int $points
     * @return float
     */
    public function pointsToDollars(int $points): float
    {
        // Convert 1 point to $0.1 (10 points = $1)
        return (float) ($points * 0.1);
    }
}
