<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'blog_post_id',
        'user_id',
        'comment',
        'status',
    ];

    // Define a scope to filter only accepted comments
    public function scopeAccepted($query)
    {
        return $query->where('status', 'accepted');
    }

    // Define a scope to filter by status
    public function scopeWithStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function blogPost()
    {
        return $this->belongsTo(BlogPost::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
