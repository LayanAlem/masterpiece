<?php


// Completing the WeatherCache model that was cut off
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeatherCache extends Model
{
    use HasFactory;

    protected $fillable = [
        'location',
        'weather_data',
        'cache_date',
    ];

    protected $casts = [
        'weather_data' => 'array',
        'cache_date' => 'date',
    ];

    public static function getWeather($location, $date = null)
    {
        $date = $date ?? now()->toDateString();

        return self::where('location', $location)
            ->where('cache_date', $date)
            ->first();
    }

    /**
     * Store weather data in cache
     */
    public static function storeWeather($location, $weatherData, $date = null)
    {
        $date = $date ?? now()->toDateString();

        return self::updateOrCreate(
            [
                'location' => $location,
                'cache_date' => $date,
            ],
            [
                'weather_data' => $weatherData,
            ]
        );
    }

    /**
     * Check if cache is fresh (less than 3 hours old)
     */
    public function isFresh()
    {
        return $this->updated_at->diffInHours(now()) < 3;
    }
}
