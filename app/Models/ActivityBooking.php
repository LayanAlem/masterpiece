<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityBooking extends Model
{
    use HasFactory;

    protected $table = 'activity_booking';

    protected $fillable = [
        'user_id',
        'activity_id',
        'booking_number',
        'ticket_count',
        'unit_price',
        'total_price',
        'booking_date',
        'activity_date',
        'activity_time',
        'status',
        'payment_status',
        'notes'
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'booking_date' => 'date',
        'activity_date' => 'date',
    ];

    /**
     * Get the user that owns the booking
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the activity associated with this booking
     */
    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

    /**
     * Get the participants for this booking
     */
    public function participants()
    {
        return $this->hasMany(ActivityParticipant::class, 'booking_id');
    }
}
