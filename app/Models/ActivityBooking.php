<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityBooking extends Model
{
    use HasFactory;

    protected $table = 'activity_booking';
    
    public $incrementing = true;

    protected $fillable = [
        'activity_id',
        'booking_id'
    ];

    /**
     * Get the activity associated with this pivot
     */
    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

    /**
     * Get the booking associated with this pivot
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
