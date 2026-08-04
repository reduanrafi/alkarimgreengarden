<?php

namespace Database\Seeders;

use App\Models\Banner;
use Illuminate\Database\Seeder;

class BannerSeeder extends Seeder
{
    public function run(): void
    {
        $banners = [
            ['title' => 'Bring Nature Indoors', 'type' => 'hero_banner', 'display_order' => 1, 'image' => 'demo/banners/indoor-plants.svg', 'button_text' => 'Shop Indoor Plants', 'short_description' => 'Easy-care greenery for every corner of your home.', 'redirect_url' => '/products?category=indoor-plants', 'background_color' => '#173d2b', 'status' => true],
            ['title' => 'Grow Your Outdoor Garden', 'type' => 'homepage_carousel', 'display_order' => 2, 'image' => 'demo/banners/outdoor-garden.svg', 'button_text' => 'Explore Outdoor Plants', 'short_description' => 'Seasonal plants and garden-ready favorites.', 'redirect_url' => '/products?category=outdoor-plants', 'background_color' => '#1f5c3f', 'status' => true],
            ['title' => 'Weekend Garden Sale', 'type' => 'homepage_carousel', 'display_order' => 3, 'image' => 'demo/banners/weekend-sale.svg', 'button_text' => 'Shop Offers', 'short_description' => 'Save on selected plants, pots and garden essentials.', 'redirect_url' => '/products?sort=discounted', 'background_color' => '#3f8a5c', 'status' => true],
            ['title' => 'Plant Care Made Simple', 'type' => 'especially_for_you', 'display_order' => 4, 'image' => 'demo/banners/plant-care.svg', 'button_text' => 'Read Care Guides', 'short_description' => 'Helpful tips to keep your plants thriving.', 'redirect_url' => '/care-guides', 'background_color' => '#6fae6e', 'status' => true],
            ['title' => 'Pots That Let Plants Shine', 'type' => 'homepage_fixed', 'display_order' => 5, 'image' => 'demo/banners/planters.svg', 'button_text' => 'Shop Planters', 'short_description' => 'Find the right home for your favorite plant.', 'redirect_url' => '/products?category=pots-planters', 'background_color' => '#e4efe4', 'status' => true],
            ['title' => 'Seasonal Garden Collection', 'type' => 'homepage_carousel', 'display_order' => 6, 'image' => 'demo/banners/seasonal.svg', 'button_text' => 'See What Is Growing', 'short_description' => 'Fresh selections for the current growing season.', 'redirect_url' => '/products?category=flowering-plants', 'background_color' => '#dce8d5', 'status' => true],
        ];

        foreach ($banners as $banner) {
            Banner::updateOrCreate(['title' => $banner['title']], $banner);
        }
    }
}
