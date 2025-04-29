<?php

namespace App\Models;

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
        'referral_code',
        'referred_by',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'loyalty_points' => 'integer',
    ];

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
     * Get all activity bookings for the user
     */
    public function activityBookings()
    {
        return $this->hasMany(ActivityBooking::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function wishlists()
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
}
