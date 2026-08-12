<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Men\'s T-Shirt',
                'slug' => 'mens-t-shirt',
                'description' => 'Stylish and comfortable t-shirts for men.',
                'status' => true,
            ],
            [
                'name' => 'Women\'s T-Shirt',
                'slug' => 'womens-t-shirt',
                'description' => 'Trendy and elegant t-shirts for women.',
                'status' => true,
            ],
            [
                'name' => 'Bags',
                'slug' => 'bags',
                'description' => 'Fashionable bags for every occasion.',
                'status' => true,
            ],
            [
                'name' => 'Others',
                'slug' => 'others',
                'description' => 'Other fashion accessories and items.',
                'status' => true,
            ],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(['slug' => $category['slug']], $category);
        }
    }
}
