<?php

namespace Database\Seeders;

use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Product;
use App\Models\ProductAttributeValue;
use Illuminate\Database\Seeder;

class AttributeSeeder extends Seeder
{
    public function run(): void
    {
        $attributes = [
            'plant-type' => 'Plant Type',
            'height' => 'Height',
            'pot-size' => 'Pot Size',
            'sunlight' => 'Sunlight',
            'water-requirement' => 'Water Requirement',
            'humidity' => 'Humidity',
            'temperature' => 'Temperature',
            'difficulty' => 'Difficulty',
            'growth-rate' => 'Growth Rate',
            'pet-friendly' => 'Pet Friendly',
            'air-purifying' => 'Air Purifying',
            'bloom-season' => 'Bloom Season',
        ];

        $models = [];
        foreach ($attributes as $slug => $name) {
            $models[$slug] = Attribute::updateOrCreate(['slug' => $slug], [
                'name' => $name,
                'status' => true,
            ]);
        }

        foreach (ProductSeeder::products() as $data) {
            $product = Product::find($data['id']);
            if (! $product) {
                continue;
            }

            foreach ($data['attributes'] as $attributeSlug => $value) {
                $attribute = $models[$attributeSlug];
                $attributeValue = AttributeValue::updateOrCreate([
                    'attribute_id' => $attribute->id,
                    'slug' => str($value)->slug()->toString(),
                ], ['value' => $value]);

                ProductAttributeValue::updateOrCreate([
                    'product_id' => $product->id,
                    'attribute_value_id' => $attributeValue->id,
                ], ['attribute_id' => $attribute->id]);
            }
        }
    }
}
