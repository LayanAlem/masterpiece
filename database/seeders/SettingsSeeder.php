<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // Referral Code Settings
            [
                'key' => 'referral_enabled',
                'value' => 'true'
            ],
            [
                'key' => 'referral_code_length',
                'value' => '8'
            ],
            [
                'key' => 'referral_reward_amount',
                'value' => '10'  // $10 reward amount per referral
            ],
            [
                'key' => 'referral_reward_type',
                'value' => 'credit'  // Credit reward type
            ],
            [
                'key' => 'referral_max_uses',
                'value' => '5'  // Maximum of 5 referrals per user
            ],
            [
                'key' => 'referral_min_purchase',
                'value' => '0'  // No purchase required
            ],

            // General Settings
            [
                'key' => 'site_name',
                'value' => 'Masterpiece'
            ],
            [
                'key' => 'contact_email',
                'value' => 'contact@masterpiece.com'
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                ['value' => $setting['value']]
            );
        }

        $this->command->info('Settings seeded successfully');
    }
}
