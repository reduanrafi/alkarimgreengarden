<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            ['category_id' => 1, 'name' => 'Classic Cotton Tee', 'slug' => 'classic-cotton-tee', 'description' => 'Soft cotton t-shirt for everyday wear.', 'fabric' => 'Cotton', 'color' => 'White', 'print' => 'Plain', 'size' => 'S, M, L, XL', 'price' => 29.99, 'stock' => 50, 'status' => true],
            ['category_id' => 1, 'name' => 'Graphic Print Tee', 'slug' => 'graphic-print-tee', 'description' => 'Bold graphic print t-shirt.', 'fabric' => 'Cotton', 'color' => 'Black', 'print' => 'Graphic', 'size' => 'M, L, XL', 'price' => 34.99, 'stock' => 40, 'status' => true],
            ['category_id' => 1, 'name' => 'Striped Polo Shirt', 'slug' => 'striped-polo-shirt', 'description' => 'Classic striped polo with collar.', 'fabric' => 'Polyester', 'color' => 'Navy Blue', 'print' => 'Striped', 'size' => 'S, M, L', 'price' => 39.99, 'stock' => 35, 'status' => true],
            ['category_id' => 1, 'name' => 'Slim Fit V-Neck', 'slug' => 'slim-fit-v-neck', 'description' => 'Modern slim fit v-neck t-shirt.', 'fabric' => 'Cotton Blend', 'color' => 'Grey', 'print' => 'Plain', 'size' => 'S, M, L, XL', 'price' => 32.99, 'stock' => 45, 'status' => true],
            ['category_id' => 2, 'name' => 'Floral Blouse', 'slug' => 'floral-blouse', 'description' => 'Elegant floral print blouse.', 'fabric' => 'Rayon', 'color' => 'Pink', 'print' => 'Floral', 'size' => 'S, M, L', 'price' => 44.99, 'stock' => 30, 'status' => true],
            ['category_id' => 2, 'name' => 'Cropped Fit Tee', 'slug' => 'cropped-fit-tee', 'description' => 'Trendy cropped fit t-shirt.', 'fabric' => 'Cotton', 'color' => 'White', 'print' => 'Plain', 'size' => 'S, M', 'price' => 27.99, 'stock' => 55, 'status' => true],
            ['category_id' => 2, 'name' => 'Off-Shoulder Top', 'slug' => 'off-shoulder-top', 'description' => 'Stylish off-shoulder design.', 'fabric' => 'Polyester', 'color' => 'Red', 'print' => 'Solid', 'size' => 'S, M, L', 'price' => 36.99, 'stock' => 25, 'status' => true],
            ['category_id' => 2, 'name' => 'Tie-Dye T-Shirt', 'slug' => 'tie-dye-t-shirt', 'description' => 'Vibrant tie-dye pattern t-shirt.', 'fabric' => 'Cotton', 'color' => 'Multi', 'print' => 'Tie-Dye', 'size' => 'S, M, L, XL', 'price' => 31.99, 'stock' => 40, 'status' => true],
            ['category_id' => 3, 'name' => 'Leather Tote Bag', 'slug' => 'leather-tote-bag', 'description' => 'Genuine leather tote bag.', 'fabric' => 'Leather', 'color' => 'Brown', 'print' => 'None', 'size' => 'One Size', 'price' => 89.99, 'stock' => 20, 'status' => true],
            ['category_id' => 3, 'name' => 'Canvas Backpack', 'slug' => 'canvas-backpack', 'description' => 'Durable canvas backpack.', 'fabric' => 'Canvas', 'color' => 'Olive Green', 'print' => 'Plain', 'size' => 'One Size', 'price' => 54.99, 'stock' => 30, 'status' => true],
            ['category_id' => 3, 'name' => 'Clutch Evening Bag', 'slug' => 'clutch-evening-bag', 'description' => 'Elegant clutch for evenings.', 'fabric' => 'Satin', 'color' => 'Gold', 'print' => 'Embellished', 'size' => 'One Size', 'price' => 49.99, 'stock' => 25, 'status' => true],
            ['category_id' => 3, 'name' => 'Crossbody Bag', 'slug' => 'crossbody-bag', 'description' => 'Compact crossbody bag.', 'fabric' => 'PU Leather', 'color' => 'Black', 'print' => 'Quilted', 'size' => 'One Size', 'price' => 42.99, 'stock' => 35, 'status' => true],
            ['category_id' => 4, 'name' => 'Silk Scarf', 'slug' => 'silk-scarf', 'description' => 'Luxurious silk scarf.', 'fabric' => 'Silk', 'color' => 'Blue', 'print' => 'Paisley', 'size' => 'One Size', 'price' => 24.99, 'stock' => 40, 'status' => true],
            ['category_id' => 4, 'name' => 'Leather Belt', 'slug' => 'leather-belt', 'description' => 'Genuine leather belt with buckle.', 'fabric' => 'Leather', 'color' => 'Black', 'print' => 'None', 'size' => 'S, M, L', 'price' => 34.99, 'stock' => 45, 'status' => true],
            ['category_id' => 4, 'name' => 'Baseball Cap', 'slug' => 'baseball-cap', 'description' => 'Casual cotton baseball cap.', 'fabric' => 'Cotton', 'color' => 'Navy', 'print' => 'Embroidered', 'size' => 'One Size', 'price' => 19.99, 'stock' => 60, 'status' => true],
            ['category_id' => 4, 'name' => 'Woven Bracelet', 'slug' => 'woven-bracelet', 'description' => 'Handwoven friendship bracelet.', 'fabric' => 'Thread', 'color' => 'Multi', 'print' => 'Woven', 'size' => 'One Size', 'price' => 12.99, 'stock' => 80, 'status' => true],
        ];

        foreach ($products as $product) {
            Product::firstOrCreate(['slug' => $product['slug']], $product);
        }
    }
}
