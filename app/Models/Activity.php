<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use Illuminate\Support\Str;

class Activity extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_type_id',
        'name',
        'description',
        'start_date',
        'end_date',
        'price',
        'cost',
        'capacity',
        'location',
        'season',
        'is_family_friendly',
        'is_accessible',
        'image',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_family_friendly' => 'boolean',
        'is_accessible' => 'boolean',
    ];

    /**
     * Get the activity bookings for this activity
     */
    public function activityBookings()
    {
        return $this->hasMany(ActivityBooking::class);
    }

    /**
     * Get the bookings for this activity through the pivot table
     */
    public function bookings()
    {
        return $this->belongsToMany(Booking::class, 'activity_booking');
    }

    /**
     * Get the category type for this activity
     */
    public function categoryType()
    {
        return $this->belongsTo(CategoryType::class);
    }

    /**
     * Get the main category for this activity through category type
     */
    public function mainCategory()
    {
        return $this->hasOneThrough(
            MainCategory::class,
            CategoryType::class,
            'id', // Foreign key on CategoryType table
            'id', // Foreign key on MainCategory table
            'category_type_id', // Local key on Activity table
            'main_category_id' // Local key on CategoryType table
        );
    }

    /**
     * Get all reviews for this activity
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get all wishlists that include this activity
     */
    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    /**
     * Get all users who have wishlisted this activity
     */
    public function usersWhoWishlisted()
    {
        return $this->belongsToMany(User::class, 'wishlists')
            ->withTimestamps();
    }

    /**
     * Get the formatted duration between start and end date
     */
    public function getFormattedDurationAttribute()
    {
        $start = Carbon::parse($this->start_date);
        $end = Carbon::parse($this->end_date);

        $totalHours = $start->diffInHours($end);
        $days = floor($totalHours / 24);
        $hours = $totalHours % 24;

        return "{$days} " . Str::plural('day', $days) . " {$hours} " . Str::plural('hour', $hours);
    }

    /**
     * Get the average rating for this activity
     */
    public function getAverageRatingAttribute()
    {
        return $this->reviews()->where('status', 'approved')->avg('rating') ?: 0;
    }

    /**
     * Get the count of reviews for this activity
     */
    public function getReviewsCountAttribute()
    {
        return $this->reviews()->where('status', 'approved')->count();
    }

    /**
     * Get upcoming dates for this activity
     */
    public function getUpcomingDatesAttribute()
    {
        // If you have a dates relationship defined
        if (method_exists($this, 'dates')) {
            return $this->dates()
                ->where('start_date', '>=', now())
                ->orderBy('start_date')
                ->get();
        }

        // Fallback if no dates relationship
        return collect();
    }

    /**
     * Get available dates for this activity
     */
    public function getAvailableDatesAttribute()
    {
        // If you have a dates relationship defined
        if (method_exists($this, 'dates')) {
            return $this->dates()
                ->where('start_date', '>=', now())
                ->where('seats_available', '>', 0)
                ->orderBy('start_date')
                ->get();
        }

        // Fallback if no dates relationship
        return collect();
    }

    /**
     * Check if this activity is upcoming
     */
    public function getIsUpcomingAttribute()
    {
        return Carbon::parse($this->start_date)->isFuture();
    }

    /**
     * Check if this activity is ongoing
     */
    public function getIsOngoingAttribute()
    {
        $now = Carbon::now();
        $startDate = Carbon::parse($this->start_date);
        $endDate = Carbon::parse($this->end_date);

        return $now->between($startDate, $endDate);
    }

    /**
     * Check if this activity is past
     */
    public function getIsPastAttribute()
    {
        return Carbon::parse($this->end_date)->isPast();
    }

    /**
     * Get remaining capacity for this activity
     */
    public function getRemainingCapacityAttribute()
    {
        // Count the number of bookings for this activity
        $bookedCount = $this->bookings()
            ->whereNotIn('status', ['cancelled', 'refunded'])
            ->count();

        // Return the difference between capacity and the number of bookings
        return max(0, $this->capacity - $bookedCount);
    }

    /**
     * Check if this activity is fully booked
     */
    public function getIsFullyBookedAttribute()
    {
        return $this->remaining_capacity <= 0;
    }

    /**
     * Get formatted price with currency
     */
    public function getFormattedPriceAttribute()
    {
        return '$' . number_format($this->price, 2);
    }

    /**
     * Scope a query to only include upcoming activities
     */
    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>', now());
    }

    /**
     * Scope a query to only include ongoing activities
     */
    public function scopeOngoing($query)
    {
        return $query->where('start_date', '<=', now())
            ->where('end_date', '>=', now());
    }

    /**
     * Scope a query to only include past activities
     */
    public function scopePast($query)
    {
        return $query->where('end_date', '<', now());
    }

    /**
     * Scope a query to only include activities with remaining capacity
     */
    public function scopeAvailable($query)
    {
        // This is a simple implementation. For more complex logic,
        // you might need to use a subquery to check actual remaining capacity
        return $query->where('start_date', '>=', now())
            ->whereRaw('capacity > (SELECT COALESCE(SUM(ticket_count), 0) FROM bookings
                        JOIN activity_booking ON bookings.id = activity_booking.booking_id
                        WHERE activity_booking.activity_id = activities.id)');
    }
}
