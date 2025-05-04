<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'activity_id',
        'booking_number',
        'ticket_count',
        'total_price',
        'discount_amount',
        'loyalty_points_earned',
        'loyalty_points_used',
        'status',
        'payment_status',
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
        'discount_amount' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the activity associated with this booking (one-to-many)
     */
    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }
    
    /**
     * Get activities associated with this booking (many-to-many)
     * This is needed for backward compatibility with existing code
     */
    public function activities()
    {
        return $this->belongsToMany(Activity::class, 'activity_booking');
    }

    /**
     * Get the participants for this booking
     */
    public function participants()
    {
        return $this->hasMany(ActivityParticipant::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }

    public function hasReview()
    {
        return $this->review()->exists();
    }

    public function canBeReviewed()
    {
        return $this->status === 'completed' && !$this->hasReview();
    }

    public function getNetTotalAttribute()
    {
        return $this->total_price - $this->discount_amount;
    }

    protected static function booted()
    {
        static::creating(function ($booking) {
            // Only generate booking number if one wasn't provided
            if (empty($booking->booking_number)) {
                do {
                    $bookingNumber = 'JT-' . now()->format('Ymd') . '-' . rand(1000, 9999);
                } while (Booking::where('booking_number', $bookingNumber)->exists());
                
                $booking->booking_number = $bookingNumber;
            }
        });
    }
}
