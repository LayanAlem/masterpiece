<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'content',
        'location',
        'image',
        'vote_count',
        'status',
        'is_winner',
    ];

    protected $casts = [
        'is_winner' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(BlogComment::class);
    }

    public function votes()
    {
        return $this->hasMany(BlogVote::class);
    }

    public function voters()
    {
        return $this->belongsToMany(User::class, 'blog_votes')
            ->withTimestamps();
    }

    public function incrementVoteCount()
    {
        $this->increment('vote_count');
    }

    public function decrementVoteCount()
    {
        // Don't allow vote count to go below 0
        if ($this->vote_count > 0) {
            $this->decrement('vote_count');
        }
    }
}
