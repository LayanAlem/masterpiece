<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
    ];

    /**
     * Get a setting value by key
     */
    public static function get(string $key, $default = null)
    {
        return Cache::remember('setting.' . $key, 60 * 60, function () use ($key, $default) {
            $setting = static::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });
    }

    /**
     * Set a setting value
     */
    public static function set(string $key, $value)
    {
        $setting = static::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );

        Cache::forget('setting.' . $key);

        return $setting;
    }

    /**
     * Initialize default settings if they don't exist
     * Call this method during seeding or installation
     */
    public static function initializeDefaults()
    {
        $defaults = [
            'referral_enabled' => 'true',
            'referral_max_uses' => '5',
            'referral_reward_amount' => '10',
            'referral_code_length' => '8',
            'loyalty_points_per_dollar' => '1',
            'loyalty_point_value' => '0.1',
        ];

        foreach ($defaults as $key => $value) {
            if (!static::where('key', $key)->exists()) {
                static::set($key, $value);
            }
        }
    }
}
