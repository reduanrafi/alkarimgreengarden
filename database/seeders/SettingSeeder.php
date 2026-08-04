<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            'website_name' => 'Green Garden',
            'website_tagline' => 'Indoor and outdoor plants for every space.',
            'footer_text' => 'Green Garden helps homes and gardens grow with healthy plants, thoughtful care and trusted essentials.',
            'copyright_text' => 'Green Garden. Grow something beautiful.',
            'meta_description' => 'Shop indoor plants, outdoor plants, planters and garden essentials at Green Garden.',
            'meta_keywords' => 'plants, indoor plants, outdoor plants, planters, gardening, plant care',
            'og_image' => 'demo/banners/green-garden-social.svg',
            'about_story' => 'Green Garden started with a simple belief: every space feels better with something growing in it. We select healthy plants and practical garden essentials for beginners and experienced growers alike.',
            'about_mission' => 'Make plant care approachable with healthy plants, honest guidance and dependable garden essentials.',
            'about_vision' => 'Help more people build greener homes, balconies and gardens.',
            'about_values' => json_encode([
                ['emoji' => '🌱', 'title' => 'Healthy Plants', 'text' => 'We focus on well-cared-for plants ready for their next home.'],
                ['emoji' => '🪴', 'title' => 'Helpful Guidance', 'text' => 'Clear care advice for every level of plant parent.'],
                ['emoji' => '🌿', 'title' => 'Grow Together', 'text' => 'A welcoming place for curious gardeners.'],
            ], JSON_UNESCAPED_UNICODE),
            'about_stats' => json_encode([
                ['emoji' => '🌿', 'title' => 'Plant First', 'text' => 'Thoughtful choices for greener spaces.'],
                ['emoji' => '☀️', 'title' => 'Care Ready', 'text' => 'Guidance from potting to pruning.'],
            ], JSON_UNESCAPED_UNICODE),
            'newsletter_heading' => 'A little greener in your inbox',
            'newsletter_subtext' => 'Get plant care tips, seasonal picks and Green Garden offers.',
        ];

        foreach ($settings as $key => $value) {
            Setting::set($key, $value);
        }
    }
}
