<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Restaurant extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'location',
        'contact_number',
        'email',
        'website',
        'image',
        'cuisine_type',
        'price_range',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the featured periods for this restaurant
     */
    public function featuredPeriods()
    {
        return $this->hasMany(FeaturedRestaurant::class);
    }

    /**
     * Check if the restaurant is currently featured
     */
    public function isCurrentlyFeatured()
    {
        return $this->featuredPeriods()
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->where('is_active', true)
            ->exists();
    }

    /**
     * Get the current feature period if it exists
     */
    public function currentFeaturePeriod()
    {
        return $this->featuredPeriods()
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->where('is_active', true)
            ->first();
    }

    /**
     * Scope a query to only include active restaurants
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include restaurants with a specific cuisine type
     */
    public function scopeOfCuisine($query, $cuisineType)
    {
        return $query->where('cuisine_type', $cuisineType);
    }

    /**
     * Scope a query to only include restaurants in a specific price range
     */
    public function scopeInPriceRange($query, $priceRange)
    {
        return $query->where('price_range', $priceRange);
    }

    /**
     * Scope a query to include restaurants that are currently featured
     */
    public function scopeFeatured($query)
    {
        $now = now();

        return $query->whereHas('featuredPeriods', function ($q) use ($now) {
            $q->where('start_date', '<=', $now)
                ->where('end_date', '>=', $now)
                ->where('is_active', true);
        });
    }
}
