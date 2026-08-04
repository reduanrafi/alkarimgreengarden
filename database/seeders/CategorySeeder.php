<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            1 => [
                'name' => 'Indoor Plants',
                'slug' => 'indoor-plants',
                'description' => 'Greenery that thrives inside your home.',
                'image' => 'demo/products/foliage-plant.svg',
                'status' => true,
            ],
            2 => [
                'name' => 'Outdoor Plants',
                'slug' => 'outdoor-plants',
                'description' => 'Trees, shrubs and blooms for your garden.',
                'image' => 'demo/products/outdoor-plant.svg',
                'status' => true,
            ],
            3 => [
                'name' => 'Flowering Plants',
                'slug' => 'flowering-plants',
                'description' => 'Blooming plants that add natural color to every room.',
                'image' => 'demo/products/flowering-plant.svg',
                'status' => true,
            ],
            4 => [
                'name' => 'Succulents',
                'slug' => 'succulents',
                'description' => 'Low-maintenance, sculptural plants for sunny spaces.',
                'image' => 'demo/products/succulent.svg',
                'status' => true,
            ],
            5 => ['name' => 'Cactus', 'slug' => 'cactus', 'description' => 'Sun-loving cacti with character and easy care.', 'image' => 'demo/products/succulent.svg', 'status' => true],
            6 => ['name' => 'Herbs', 'slug' => 'herbs', 'description' => 'Fresh culinary herbs for windowsills and gardens.', 'image' => 'demo/products/herb.svg', 'status' => true],
            7 => ['name' => 'Fruit Plants', 'slug' => 'fruit-plants', 'description' => 'Fruit-bearing plants for productive home gardens.', 'image' => 'demo/products/outdoor-plant.svg', 'status' => true],
            8 => ['name' => 'Vegetable Plants', 'slug' => 'vegetable-plants', 'description' => 'Healthy vegetable starters for every season.', 'image' => 'demo/products/outdoor-plant.svg', 'status' => true],
            9 => ['name' => 'Medicinal Plants', 'slug' => 'medicinal-plants', 'description' => 'Useful plants valued for traditional home care.', 'image' => 'demo/products/succulent.svg', 'status' => true],
            10 => ['name' => 'Bonsai', 'slug' => 'bonsai', 'description' => 'Living miniature trees for patient plant lovers.', 'image' => 'demo/products/bonsai.svg', 'status' => true],
            11 => ['name' => 'Hanging Plants', 'slug' => 'hanging-plants', 'description' => 'Trailing plants made for shelves, hooks and baskets.', 'image' => 'demo/products/foliage-plant.svg', 'status' => true],
            12 => ['name' => 'Air Purifying Plants', 'slug' => 'air-purifying-plants', 'description' => 'Easy-care greenery selected for healthier indoor spaces.', 'image' => 'demo/products/foliage-plant.svg', 'status' => true],
            13 => ['name' => 'Seeds', 'slug' => 'seeds', 'description' => 'Reliable seeds for flowers, herbs and vegetables.', 'image' => 'demo/products/seeds.svg', 'status' => true],
            14 => ['name' => 'Fertilizers', 'slug' => 'fertilizers', 'description' => 'Plant nutrition for stronger roots and fuller growth.', 'image' => 'demo/products/garden-care.svg', 'status' => true],
            15 => ['name' => 'Garden Tools', 'slug' => 'garden-tools', 'description' => 'Practical tools for planting, pruning and upkeep.', 'image' => 'demo/products/garden-care.svg', 'status' => true],
            16 => ['name' => 'Pots & Planters', 'slug' => 'pots-planters', 'description' => 'Planters and pots to complement every plant.', 'image' => 'demo/products/planter.svg', 'status' => true],
        ];

        foreach ($categories as $id => $category) {
            $model = Category::withTrashed()->updateOrCreate(['id' => $id], $category);

            if ($model->trashed()) {
                $model->restore();
            }
        }
    }
}
