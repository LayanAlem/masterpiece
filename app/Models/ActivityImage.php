<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'activity_id',
        'path',
        'is_primary',
        'display_order',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    /**
     * Get the activity that owns the image
     */
    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }
}
