<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityParticipant extends Model
{
    use HasFactory;

    protected $fillable = [
        'activity_id',
        'booking_id',
        'name',
        'email',
        'phone',
    ];

    /**
     * Get the activity that this participant belongs to
     */
    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

    /**
     * Get the booking that this participant belongs to
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }
}
