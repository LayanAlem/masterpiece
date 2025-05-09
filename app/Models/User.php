<?php

namespace App\Models;

use App\Services\ReferralService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens, SoftDeletes, HasFactory, Notifiable;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'google_id',
        'profile_image',
        'phone',
        'loyalty_points',
        'used_points',
        'referral_code',
        'referred_by',
        'referral_balance',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'loyalty_points' => 'integer',
        'referral_balance' => 'decimal:2',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Generate a referral code for new users
        static::creating(function (User $user) {
            if (empty($user->referral_code) && !empty($user->first_name)) {
                $referralService = new ReferralService();
                $user->referral_code = $referralService->generateReferralCode($user);
            }
        });
    }

    public function referrer()
    {
        return $this->belongsTo(User::class, 'referred_by');
    }

    public function referrals()
    {
        return $this->hasMany(User::class, 'referred_by');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Get all activity bookings for the user through the bookings relationship
     */
    public function activityBookings()
    {
        // Use hasManyThrough to access activity bookings through the bookings relationship
        return $this->hasManyThrough(
            ActivityBooking::class,
            Booking::class,
            'user_id',  // Foreign key on bookings table
            'booking_id',  // Foreign key on activity_booking table
            'id',  // Local key on users table
            'id'   // Local key on bookings table
        );
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    /**
     * Get the user's wishlist items
     */
    public function wishlist()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function blogPosts()
    {
        return $this->hasMany(BlogPost::class);
    }

    public function blogComments()
    {
        return $this->hasMany(BlogComment::class);
    }

    public function blogVotes()
    {
        return $this->hasMany(BlogVote::class);
    }

    public function hasVotedFor(BlogPost $blogPost)
    {
        return $this->blogVotes()->where('blog_post_id', $blogPost->id)->exists();
    }

    public function wishlistedActivities()
    {
        return $this->belongsToMany(Activity::class, 'wishlists')
            ->withTimestamps();
    }

    /**
     * Get the total number of points earned from referrals
     *
     * @return float
     */
    public function getReferralPointsAttribute(): float
    {
        // Calculate referral rewards at $10 per referral
        // Limited to 5 referrals maximum
        $referralCount = min($this->referrals->count(), (int) Setting::get('referral_max_uses', 5));
        $rewardAmount = (float) Setting::get('referral_reward_amount', 10);

        return $referralCount * $rewardAmount;
    }

    /**
     * Get the number of remaining referrals allowed
     *
     * @return int
     */
    public function getRemainingReferralsAttribute(): int
    {
        $maxReferrals = (int) Setting::get('referral_max_uses', 5);
        return max(0, $maxReferrals - $this->referrals->count());
    }

    /**
     * Get available (unused) loyalty points
     *
     * @return int
     */
    public function getAvailablePointsAttribute(): int
    {
        return max(0, $this->loyalty_points - $this->used_points);
    }

    /**
     * Get the tier/generation of this user in the referral system
     *
     * @return int
     */
    public function getReferralTierAttribute(): int
    {
        $referralService = new ReferralService();
        return $referralService->getReferralTier($this);
    }

    /**
     * Get all users referred by this user at a specific tier
     *
     * @param int $tier
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getTierReferrals(int $tier = 1)
    {
        $referralService = new ReferralService();
        return $referralService->getReferralsByTier($this, $tier);
    }

    /**
     * Check if this user should continue to receive multi-level referral rewards
     *
     * @return bool
     */
    public function canContinueReferralIteration(): bool
    {
        $referralService = new ReferralService();
        return $referralService->continueToIterate($this);
    }

    /**
     * Get multi-tier referral rewards for this user
     *
     * @return array
     */
    public function getMultiLevelReferralRewards(): array
    {
        $referralService = new ReferralService();
        $rewards = [];
        $maxTiers = (int) Setting::get('referral_max_tiers', 3);

        for ($tier = 1; $tier <= $maxTiers; $tier++) {
            $referrals = $this->getTierReferrals($tier);
            $rewardAmount = $referralService->calculateTierReward($tier);
            $rewards[$tier] = [
                'count' => $referrals->count(),
                'rate' => $rewardAmount,
                'total' => $referrals->count() * $rewardAmount
            ];
        }

        return $rewards;
    }
}
