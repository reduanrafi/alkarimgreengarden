<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        foreach (self::products() as $product) {
            Product::updateOrCreate(
                ['id' => $product['id']],
                Arr::except($product, ['id', 'attributes'])
            );
        }
    }

    public static function products(): array
    {
        return [
            [
                'id' => 1, 'category_id' => 1, 'name' => 'Monstera Deliciosa', 'slug' => 'monstera-deliciosa', 'brand' => 'Urban Leaf Nursery',
                'description' => 'A bold split-leaf houseplant that instantly makes a room feel alive.',
                'full_description' => 'Monstera deliciosa is an easy-going tropical houseplant with dramatic fenestrated leaves. Place it in bright, indirect light and let the top layer of soil dry before watering. Supplied in a nursery pot ready to style in your favorite planter.',
                'fabric' => 'Bright, indirect light', 'color' => 'Deep Green', 'print' => 'Split Leaf', 'size' => '8 inch nursery pot', 'price' => 34.99, 'buying_price' => 18.00, 'discount_price' => 29.99, 'discount_type' => 'fixed', 'stock' => 32, 'low_stock_alert_quantity' => 5, 'sku' => 'GG-PLT-MON-001', 'barcode' => '8901000000011', 'image' => 'demo/products/foliage-plant.svg', 'gallery_images' => ['demo/products/foliage-plant.svg'], 'featured' => true, 'status' => true, 'pre_order' => false,
                'meta_title' => 'Monstera Deliciosa Indoor Plant | Green Garden', 'meta_description' => 'Shop a healthy Monstera deliciosa in an 8 inch nursery pot from Green Garden.', 'meta_keywords' => 'monstera, indoor plant, tropical plant, split leaf plant',
                'attributes' => ['plant-type' => 'Indoor Foliage', 'height' => '45-60 cm', 'pot-size' => '8 inch', 'sunlight' => 'Bright, indirect light', 'water-requirement' => 'Moderate', 'humidity' => 'Medium to High', 'temperature' => '18-30 C', 'difficulty' => 'Easy', 'growth-rate' => 'Fast', 'pet-friendly' => 'No', 'air-purifying' => 'Yes', 'bloom-season' => 'Rare indoors'],
            ],
            [
                'id' => 2, 'category_id' => 2, 'name' => 'Bird of Paradise', 'slug' => 'bird-of-paradise', 'brand' => 'Green Canopy Nursery',
                'description' => 'A sculptural tropical plant with broad leaves for bright patios and sunny rooms.',
                'full_description' => 'Bird of Paradise brings a lush, architectural look to warm, bright spaces. Give it a sunny position, water when the upper soil dries, and rotate it regularly for even growth. Mature plants may reward patient growers with distinctive blooms.',
                'fabric' => 'Bright light to full sun', 'color' => 'Glossy Green', 'print' => 'Tropical Foliage', 'size' => '10 inch nursery pot', 'price' => 59.99, 'buying_price' => 34.00, 'discount_price' => null, 'discount_type' => null, 'stock' => 18, 'low_stock_alert_quantity' => 4, 'sku' => 'GG-PLT-BOP-002', 'barcode' => '8901000000028', 'image' => 'demo/products/outdoor-plant.svg', 'gallery_images' => ['demo/products/outdoor-plant.svg'], 'featured' => true, 'status' => true, 'pre_order' => false,
                'meta_title' => 'Bird of Paradise Plant | Green Garden', 'meta_description' => 'Bring home a dramatic Bird of Paradise for a bright indoor or covered outdoor space.', 'meta_keywords' => 'bird of paradise, tropical plant, outdoor plant, large plant',
                'attributes' => ['plant-type' => 'Outdoor Tropical', 'height' => '90-120 cm', 'pot-size' => '10 inch', 'sunlight' => 'Bright light to full sun', 'water-requirement' => 'Moderate', 'humidity' => 'Medium', 'temperature' => '18-32 C', 'difficulty' => 'Moderate', 'growth-rate' => 'Moderate', 'pet-friendly' => 'No', 'air-purifying' => 'Yes', 'bloom-season' => 'Spring to Summer'],
            ],
            [
                'id' => 3, 'category_id' => 3, 'name' => 'Peace Lily', 'slug' => 'peace-lily', 'brand' => 'Eden Roots',
                'description' => 'Elegant dark foliage with graceful white flowers for low-light corners.',
                'full_description' => 'Peace Lily is a classic indoor flowering plant valued for its deep green leaves and white spathes. It adapts well to gentle indirect light and lets you know it needs a drink with a slight droop. Keep away from pets.',
                'fabric' => 'Low to medium indirect light', 'color' => 'Deep Green and White', 'print' => 'Flowering', 'size' => '6 inch nursery pot', 'price' => 24.99, 'buying_price' => 12.00, 'discount_price' => null, 'discount_type' => null, 'stock' => 27, 'low_stock_alert_quantity' => 5, 'sku' => 'GG-PLT-PLL-003', 'barcode' => '8901000000035', 'image' => 'demo/products/flowering-plant.svg', 'gallery_images' => ['demo/products/flowering-plant.svg'], 'featured' => true, 'status' => true, 'pre_order' => false,
                'meta_title' => 'Peace Lily Flowering Plant | Green Garden', 'meta_description' => 'Order a low-light-friendly Peace Lily with elegant white blooms.', 'meta_keywords' => 'peace lily, flowering plant, indoor plant, air purifying plant',
                'attributes' => ['plant-type' => 'Flowering Indoor Plant', 'height' => '35-45 cm', 'pot-size' => '6 inch', 'sunlight' => 'Low to medium indirect light', 'water-requirement' => 'Moderate', 'humidity' => 'Medium to High', 'temperature' => '18-28 C', 'difficulty' => 'Easy', 'growth-rate' => 'Moderate', 'pet-friendly' => 'No', 'air-purifying' => 'Yes', 'bloom-season' => 'Year round indoors'],
            ],
            [
                'id' => 4, 'category_id' => 4, 'name' => 'Echeveria Rosette', 'slug' => 'echeveria-rosette', 'brand' => 'Desert Bloom Nursery',
                'description' => 'A compact rosette succulent with soft blue-green leaves and minimal care needs.',
                'full_description' => 'Echeveria is a sun-loving succulent that thrives with bright light and infrequent watering. Its tidy rosette shape makes it perfect for desks, windowsills and small planter arrangements. Always let the potting mix dry completely between drinks.',
                'fabric' => 'Full sun to bright light', 'color' => 'Blue Green', 'print' => 'Rosette', 'size' => '4 inch nursery pot', 'price' => 12.99, 'buying_price' => 5.50, 'discount_price' => null, 'discount_type' => null, 'stock' => 48, 'low_stock_alert_quantity' => 8, 'sku' => 'GG-PLT-ECH-004', 'barcode' => '8901000000042', 'image' => 'demo/products/succulent.svg', 'gallery_images' => ['demo/products/succulent.svg'], 'featured' => false, 'status' => true, 'pre_order' => false,
                'meta_title' => 'Echeveria Rosette Succulent | Green Garden', 'meta_description' => 'Buy an easy-care Echeveria rosette succulent in a 4 inch nursery pot.', 'meta_keywords' => 'echeveria, succulent, low water plant, desk plant',
                'attributes' => ['plant-type' => 'Succulent', 'height' => '10-15 cm', 'pot-size' => '4 inch', 'sunlight' => 'Full sun to bright light', 'water-requirement' => 'Low', 'humidity' => 'Low', 'temperature' => '15-32 C', 'difficulty' => 'Easy', 'growth-rate' => 'Slow', 'pet-friendly' => 'Yes', 'air-purifying' => 'No', 'bloom-season' => 'Spring'],
            ],
            [
                'id' => 5, 'category_id' => 5, 'name' => 'Golden Barrel Cactus', 'slug' => 'golden-barrel-cactus', 'brand' => 'Desert Bloom Nursery',
                'description' => 'A round, golden-spined cactus for bright windows and dry gardens.',
                'full_description' => 'Golden Barrel Cactus has a distinct rounded form and thrives in warm, sunny conditions. Use a fast-draining cactus mix and water only after the soil is fully dry. It is a reliable low-maintenance statement for sunny spots.',
                'fabric' => 'Full sun', 'color' => 'Green and Gold', 'print' => 'Barrel Form', 'size' => '5 inch nursery pot', 'price' => 18.99, 'buying_price' => 8.00, 'discount_price' => null, 'discount_type' => null, 'stock' => 22, 'low_stock_alert_quantity' => 4, 'sku' => 'GG-PLT-GBC-005', 'barcode' => '8901000000059', 'image' => 'demo/products/succulent.svg', 'gallery_images' => ['demo/products/succulent.svg'], 'featured' => false, 'status' => true, 'pre_order' => false,
                'meta_title' => 'Golden Barrel Cactus | Green Garden', 'meta_description' => 'Shop a Golden Barrel Cactus for a warm, bright and low-water space.', 'meta_keywords' => 'golden barrel cactus, cactus, sun plant, drought tolerant',
                'attributes' => ['plant-type' => 'Cactus', 'height' => '15-20 cm', 'pot-size' => '5 inch', 'sunlight' => 'Full sun', 'water-requirement' => 'Low', 'humidity' => 'Low', 'temperature' => '18-35 C', 'difficulty' => 'Easy', 'growth-rate' => 'Slow', 'pet-friendly' => 'Yes', 'air-purifying' => 'No', 'bloom-season' => 'Summer'],
            ],
            [
                'id' => 6, 'category_id' => 6, 'name' => 'Sweet Basil Plant', 'slug' => 'sweet-basil-plant', 'brand' => 'Fresh Start Nursery',
                'description' => 'Fresh aromatic basil ready for a sunny kitchen garden or balcony.',
                'full_description' => 'Sweet Basil is an easy edible herb with tender, fragrant leaves. Give it sun, regular moisture and frequent harvesting to encourage bushy new growth. Ideal for containers close to the kitchen.',
                'fabric' => 'Full sun', 'color' => 'Bright Green', 'print' => 'Culinary Herb', 'size' => '4 inch nursery pot', 'price' => 8.99, 'buying_price' => 3.50, 'discount_price' => null, 'discount_type' => null, 'stock' => 40, 'low_stock_alert_quantity' => 8, 'sku' => 'GG-HRB-BAS-006', 'barcode' => '8901000000066', 'image' => 'demo/products/herb.svg', 'gallery_images' => ['demo/products/herb.svg'], 'featured' => false, 'status' => true, 'pre_order' => false,
                'meta_title' => 'Sweet Basil Plant | Green Garden', 'meta_description' => 'Grow fresh aromatic basil at home with this healthy starter plant.', 'meta_keywords' => 'sweet basil, herb plant, edible plant, kitchen garden',
                'attributes' => ['plant-type' => 'Culinary Herb', 'height' => '20-30 cm', 'pot-size' => '4 inch', 'sunlight' => 'Full sun', 'water-requirement' => 'Moderate', 'humidity' => 'Medium', 'temperature' => '20-32 C', 'difficulty' => 'Easy', 'growth-rate' => 'Fast', 'pet-friendly' => 'Yes', 'air-purifying' => 'No', 'bloom-season' => 'Summer'],
            ],
            [
                'id' => 7, 'category_id' => 7, 'name' => 'Meyer Lemon Tree', 'slug' => 'meyer-lemon-tree', 'brand' => 'Green Canopy Nursery',
                'description' => 'A fragrant citrus tree for sunny balconies, patios and gardens.',
                'full_description' => 'Meyer Lemon is a compact citrus tree with glossy foliage, scented blossoms and sweet-tart fruit. Keep it in a bright sunny place, feed it during the growing season and protect it from frost.',
                'fabric' => 'Full sun', 'color' => 'Green', 'print' => 'Citrus Tree', 'size' => '10 inch nursery pot', 'price' => 69.99, 'buying_price' => 40.00, 'discount_price' => 59.99, 'discount_type' => 'fixed', 'stock' => 12, 'low_stock_alert_quantity' => 3, 'sku' => 'GG-FRT-LEM-007', 'barcode' => '8901000000073', 'image' => 'demo/products/outdoor-plant.svg', 'gallery_images' => ['demo/products/outdoor-plant.svg'], 'featured' => true, 'status' => true, 'pre_order' => false,
                'meta_title' => 'Meyer Lemon Tree | Green Garden', 'meta_description' => 'Shop a Meyer Lemon Tree for a sunny patio, balcony or home garden.', 'meta_keywords' => 'meyer lemon, citrus tree, fruit plant, patio plant',
                'attributes' => ['plant-type' => 'Fruit Plant', 'height' => '80-100 cm', 'pot-size' => '10 inch', 'sunlight' => 'Full sun', 'water-requirement' => 'Moderate', 'humidity' => 'Medium', 'temperature' => '16-32 C', 'difficulty' => 'Moderate', 'growth-rate' => 'Moderate', 'pet-friendly' => 'No', 'air-purifying' => 'No', 'bloom-season' => 'Spring to Summer'],
            ],
            [
                'id' => 8, 'category_id' => 8, 'name' => 'Cherry Tomato Plant', 'slug' => 'cherry-tomato-plant', 'brand' => 'Fresh Start Nursery',
                'description' => 'A productive vegetable starter for sunny containers and garden beds.',
                'full_description' => 'Cherry Tomato plants are compact, vigorous and rewarding for beginners. Plant in a sunny spot, provide a stake or cage, water consistently and harvest sweet fruit as it ripens.',
                'fabric' => 'Full sun', 'color' => 'Green', 'print' => 'Vegetable Starter', 'size' => '5 inch nursery pot', 'price' => 10.99, 'buying_price' => 4.00, 'discount_price' => null, 'discount_type' => null, 'stock' => 35, 'low_stock_alert_quantity' => 6, 'sku' => 'GG-VGT-TOM-008', 'barcode' => '8901000000080', 'image' => 'demo/products/outdoor-plant.svg', 'gallery_images' => ['demo/products/outdoor-plant.svg'], 'featured' => false, 'status' => true, 'pre_order' => false,
                'meta_title' => 'Cherry Tomato Plant | Green Garden', 'meta_description' => 'Start a sunny home harvest with a healthy Cherry Tomato plant.', 'meta_keywords' => 'cherry tomato, vegetable plant, edible garden, tomato starter',
                'attributes' => ['plant-type' => 'Vegetable Plant', 'height' => '30-40 cm', 'pot-size' => '5 inch', 'sunlight' => 'Full sun', 'water-requirement' => 'Moderate', 'humidity' => 'Medium', 'temperature' => '18-32 C', 'difficulty' => 'Easy', 'growth-rate' => 'Fast', 'pet-friendly' => 'No', 'air-purifying' => 'No', 'bloom-season' => 'Summer'],
            ],
            [
                'id' => 9, 'category_id' => 9, 'name' => 'Aloe Vera', 'slug' => 'aloe-vera', 'brand' => 'Eden Roots',
                'description' => 'A useful, sun-loving succulent with soothing gel-filled leaves.',
                'full_description' => 'Aloe Vera is a practical low-water plant with upright fleshy leaves. It thrives near a sunny window and needs only occasional watering. Its tidy form works well in small pots and bright kitchens.',
                'fabric' => 'Bright light to full sun', 'color' => 'Silver Green', 'print' => 'Medicinal Succulent', 'size' => '6 inch nursery pot', 'price' => 16.99, 'buying_price' => 7.00, 'discount_price' => null, 'discount_type' => null, 'stock' => 38, 'low_stock_alert_quantity' => 6, 'sku' => 'GG-MED-ALO-009', 'barcode' => '8901000000097', 'image' => 'demo/products/succulent.svg', 'gallery_images' => ['demo/products/succulent.svg'], 'featured' => false, 'status' => true, 'pre_order' => false,
                'meta_title' => 'Aloe Vera Medicinal Plant | Green Garden', 'meta_description' => 'Buy an easy-care Aloe Vera plant for a bright windowsill.', 'meta_keywords' => 'aloe vera, medicinal plant, succulent, low water plant',
                'attributes' => ['plant-type' => 'Medicinal Succulent', 'height' => '25-35 cm', 'pot-size' => '6 inch', 'sunlight' => 'Bright light to full sun', 'water-requirement' => 'Low', 'humidity' => 'Low', 'temperature' => '18-32 C', 'difficulty' => 'Easy', 'growth-rate' => 'Moderate', 'pet-friendly' => 'No', 'air-purifying' => 'Yes', 'bloom-season' => 'Summer'],
            ],
            [
                'id' => 10, 'category_id' => 10, 'name' => 'Ficus Ginseng Bonsai', 'slug' => 'ficus-ginseng-bonsai', 'brand' => 'Bonsai Grove',
                'description' => 'A characterful bonsai with sculptural roots and glossy foliage.',
                'full_description' => 'Ficus Ginseng Bonsai is a forgiving introduction to bonsai. It prefers bright indirect light, measured watering and occasional pruning to retain its compact silhouette. Each plant has its own naturally distinctive trunk.',
                'fabric' => 'Bright, indirect light', 'color' => 'Deep Green', 'print' => 'Bonsai Tree', 'size' => '6 inch ceramic pot', 'price' => 44.99, 'buying_price' => 24.00, 'discount_price' => null, 'discount_type' => null, 'stock' => 14, 'low_stock_alert_quantity' => 3, 'sku' => 'GG-BON-FIC-010', 'barcode' => '8901000000103', 'image' => 'demo/products/bonsai.svg', 'gallery_images' => ['demo/products/bonsai.svg'], 'featured' => true, 'status' => true, 'pre_order' => false,
                'meta_title' => 'Ficus Ginseng Bonsai | Green Garden', 'meta_description' => 'Shop a sculptural Ficus Ginseng Bonsai for a calm, living focal point.', 'meta_keywords' => 'ficus ginseng, bonsai, indoor tree, bonsai plant',
                'attributes' => ['plant-type' => 'Bonsai', 'height' => '25-35 cm', 'pot-size' => '6 inch', 'sunlight' => 'Bright, indirect light', 'water-requirement' => 'Moderate', 'humidity' => 'Medium', 'temperature' => '18-28 C', 'difficulty' => 'Moderate', 'growth-rate' => 'Slow', 'pet-friendly' => 'No', 'air-purifying' => 'Yes', 'bloom-season' => 'Not applicable'],
            ],
            [
                'id' => 11, 'category_id' => 11, 'name' => 'Golden Pothos Hanging Plant', 'slug' => 'golden-pothos-hanging-plant', 'brand' => 'Urban Leaf Nursery',
                'description' => 'An adaptable trailing vine with heart-shaped green and gold leaves.',
                'full_description' => 'Golden Pothos is one of the easiest trailing houseplants to grow. Let its vines cascade from a shelf or hanging basket, give it indirect light and water when the top soil feels dry. It tolerates a wide range of home conditions.',
                'fabric' => 'Low to bright indirect light', 'color' => 'Green and Gold', 'print' => 'Trailing Vine', 'size' => '6 inch hanging pot', 'price' => 21.99, 'buying_price' => 10.00, 'discount_price' => 18.99, 'discount_type' => 'fixed', 'stock' => 31, 'low_stock_alert_quantity' => 5, 'sku' => 'GG-HNG-POT-011', 'barcode' => '8901000000110', 'image' => 'demo/products/foliage-plant.svg', 'gallery_images' => ['demo/products/foliage-plant.svg'], 'featured' => true, 'status' => true, 'pre_order' => false,
                'meta_title' => 'Golden Pothos Hanging Plant | Green Garden', 'meta_description' => 'Shop a lush Golden Pothos in a ready-to-hang pot.', 'meta_keywords' => 'golden pothos, hanging plant, trailing plant, indoor plant',
                'attributes' => ['plant-type' => 'Hanging Plant', 'height' => '30-45 cm trails', 'pot-size' => '6 inch', 'sunlight' => 'Low to bright indirect light', 'water-requirement' => 'Moderate', 'humidity' => 'Medium', 'temperature' => '18-30 C', 'difficulty' => 'Easy', 'growth-rate' => 'Fast', 'pet-friendly' => 'No', 'air-purifying' => 'Yes', 'bloom-season' => 'Rare indoors'],
            ],
            [
                'id' => 12, 'category_id' => 12, 'name' => 'Snake Plant Laurentii', 'slug' => 'snake-plant-laurentii', 'brand' => 'Eden Roots',
                'description' => 'A resilient upright plant with striking gold-edged leaves.',
                'full_description' => 'Snake Plant Laurentii is a low-maintenance favorite for homes and offices. Its upright foliage tolerates low light, missed waterings and dry indoor air. Use a free-draining mix and let soil dry fully between waterings.',
                'fabric' => 'Low to bright indirect light', 'color' => 'Green and Gold', 'print' => 'Variegated Upright', 'size' => '8 inch nursery pot', 'price' => 27.99, 'buying_price' => 13.00, 'discount_price' => null, 'discount_type' => null, 'stock' => 45, 'low_stock_alert_quantity' => 7, 'sku' => 'GG-AIR-SNK-012', 'barcode' => '8901000000127', 'image' => 'demo/products/foliage-plant.svg', 'gallery_images' => ['demo/products/foliage-plant.svg'], 'featured' => true, 'status' => true, 'pre_order' => false,
                'meta_title' => 'Snake Plant Laurentii | Green Garden', 'meta_description' => 'Order a hardy Snake Plant Laurentii for low-maintenance indoor greenery.', 'meta_keywords' => 'snake plant, air purifying plant, low light plant, sansevieria',
                'attributes' => ['plant-type' => 'Air Purifying Plant', 'height' => '50-65 cm', 'pot-size' => '8 inch', 'sunlight' => 'Low to bright indirect light', 'water-requirement' => 'Low', 'humidity' => 'Low to Medium', 'temperature' => '16-30 C', 'difficulty' => 'Easy', 'growth-rate' => 'Slow', 'pet-friendly' => 'No', 'air-purifying' => 'Yes', 'bloom-season' => 'Rare indoors'],
            ],
            [
                'id' => 13, 'category_id' => 13, 'name' => 'Sunflower Seeds - Golden Giant', 'slug' => 'sunflower-seeds-golden-giant', 'brand' => 'Green Garden Seeds',
                'description' => 'A cheerful packet of tall, golden sunflower seeds for sunny gardens.',
                'full_description' => 'Golden Giant Sunflower seeds produce tall stems and broad sunny faces that attract pollinators. Sow after frost in a sunny bed or a deep container, keep soil evenly moist during germination and support plants as they grow.',
                'fabric' => 'Full sun', 'color' => 'Golden Yellow', 'print' => 'Flower Seed', 'size' => '25 seed packet', 'price' => 4.99, 'buying_price' => 1.50, 'discount_price' => null, 'discount_type' => null, 'stock' => 80, 'low_stock_alert_quantity' => 12, 'sku' => 'GG-SED-SUN-013', 'barcode' => '8901000000134', 'image' => 'demo/products/seeds.svg', 'gallery_images' => ['demo/products/seeds.svg'], 'featured' => false, 'status' => true, 'pre_order' => false,
                'meta_title' => 'Golden Giant Sunflower Seeds | Green Garden', 'meta_description' => 'Grow tall golden sunflowers with this 25 seed packet.', 'meta_keywords' => 'sunflower seeds, flower seeds, garden seeds, pollinator garden',
                'attributes' => ['plant-type' => 'Flower Seeds', 'height' => '180-240 cm', 'pot-size' => '25 seed packet', 'sunlight' => 'Full sun', 'water-requirement' => 'Moderate', 'humidity' => 'Outdoor conditions', 'temperature' => '18-30 C', 'difficulty' => 'Easy', 'growth-rate' => 'Fast', 'pet-friendly' => 'Yes', 'air-purifying' => 'No', 'bloom-season' => 'Summer to Fall'],
            ],
            [
                'id' => 14, 'category_id' => 14, 'name' => 'Organic All Purpose Fertilizer', 'slug' => 'organic-all-purpose-fertilizer', 'brand' => 'Root & Rise',
                'description' => 'Balanced organic nutrition for healthier leaves, roots and blooms.',
                'full_description' => 'Root & Rise Organic All Purpose Fertilizer is a gentle, balanced blend for indoor and outdoor plants. Apply as directed during active growth and water in thoroughly. Suitable for established container plants and garden beds.',
                'fabric' => 'Not applicable', 'color' => 'Natural Brown', 'print' => 'Organic Granules', 'size' => '1 kg bag', 'price' => 14.99, 'buying_price' => 6.50, 'discount_price' => null, 'discount_type' => null, 'stock' => 55, 'low_stock_alert_quantity' => 10, 'sku' => 'GG-FRT-ORG-014', 'barcode' => '8901000000141', 'image' => 'demo/products/garden-care.svg', 'gallery_images' => ['demo/products/garden-care.svg'], 'featured' => false, 'status' => true, 'pre_order' => false,
                'meta_title' => 'Organic All Purpose Fertilizer | Green Garden', 'meta_description' => 'Feed indoor and outdoor plants with a balanced organic fertilizer.', 'meta_keywords' => 'organic fertilizer, plant food, garden fertilizer, soil care',
                'attributes' => ['plant-type' => 'Plant Nutrition', 'height' => 'Not applicable', 'pot-size' => '1 kg bag', 'sunlight' => 'Not applicable', 'water-requirement' => 'Water in after application', 'humidity' => 'Not applicable', 'temperature' => 'Store below 30 C', 'difficulty' => 'Easy', 'growth-rate' => 'Not applicable', 'pet-friendly' => 'Store safely', 'air-purifying' => 'No', 'bloom-season' => 'Use during growing season'],
            ],
            [
                'id' => 15, 'category_id' => 15, 'name' => 'Stainless Steel Garden Trowel', 'slug' => 'stainless-steel-garden-trowel', 'brand' => 'Root & Rise',
                'description' => 'A durable hand trowel for potting, planting and light garden work.',
                'full_description' => 'This stainless steel garden trowel has a comfortable wooden handle and a shaped blade for transplanting, loosening soil and filling pots. Rinse and dry after use to keep it ready for your next planting session.',
                'fabric' => 'Not applicable', 'color' => 'Steel and Wood', 'print' => 'Hand Tool', 'size' => '30 cm length', 'price' => 17.99, 'buying_price' => 8.00, 'discount_price' => null, 'discount_type' => null, 'stock' => 29, 'low_stock_alert_quantity' => 5, 'sku' => 'GG-TOL-TRW-015', 'barcode' => '8901000000158', 'image' => 'demo/products/garden-care.svg', 'gallery_images' => ['demo/products/garden-care.svg'], 'featured' => false, 'status' => true, 'pre_order' => false,
                'meta_title' => 'Stainless Steel Garden Trowel | Green Garden', 'meta_description' => 'Shop a durable stainless steel hand trowel for everyday garden tasks.', 'meta_keywords' => 'garden trowel, gardening tool, hand tool, planting tool',
                'attributes' => ['plant-type' => 'Garden Tool', 'height' => '30 cm length', 'pot-size' => 'One size', 'sunlight' => 'Not applicable', 'water-requirement' => 'Clean after use', 'humidity' => 'Store dry', 'temperature' => 'Not applicable', 'difficulty' => 'Easy', 'growth-rate' => 'Not applicable', 'pet-friendly' => 'Store safely', 'air-purifying' => 'No', 'bloom-season' => 'Year round'],
            ],
            [
                'id' => 16, 'category_id' => 16, 'name' => 'Sage Ceramic Planter with Saucer', 'slug' => 'sage-ceramic-planter-with-saucer', 'brand' => 'Pot & Petal',
                'description' => 'A matte sage ceramic planter with a matching drainage saucer.',
                'full_description' => 'This calm sage ceramic planter gives houseplants a finished look while the matching saucer helps protect surfaces. Its drainage hole supports healthy roots and it comfortably fits most 6 inch nursery pots.',
                'fabric' => 'Not applicable', 'color' => 'Sage Green', 'print' => 'Matte Ceramic', 'size' => '6 inch', 'price' => 22.99, 'buying_price' => 10.00, 'discount_price' => 19.99, 'discount_type' => 'fixed', 'stock' => 36, 'low_stock_alert_quantity' => 6, 'sku' => 'GG-POT-SGE-016', 'barcode' => '8901000000165', 'image' => 'demo/products/planter.svg', 'gallery_images' => ['demo/products/planter.svg'], 'featured' => true, 'status' => true, 'pre_order' => false,
                'meta_title' => 'Sage Ceramic Planter with Saucer | Green Garden', 'meta_description' => 'Give your houseplants a beautiful home with this sage ceramic planter and saucer.', 'meta_keywords' => 'ceramic planter, sage planter, plant pot, planter with saucer',
                'attributes' => ['plant-type' => 'Planter', 'height' => '15 cm', 'pot-size' => '6 inch', 'sunlight' => 'Not applicable', 'water-requirement' => 'Drain excess water', 'humidity' => 'Not applicable', 'temperature' => 'Indoor or covered outdoor', 'difficulty' => 'Easy', 'growth-rate' => 'Not applicable', 'pet-friendly' => 'Yes', 'air-purifying' => 'No', 'bloom-season' => 'Year round'],
            ],
        ];
    }
}
