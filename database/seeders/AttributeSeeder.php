<?php

namespace Database\Seeders;

use App\Models\Attribute;
use App\Models\AttributeValue;
use Illuminate\Database\Seeder;

class AttributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $attributes = [
            [
                'name' => 'Color',
                'values' => ['Black', 'Blue', 'Green', 'Red', 'Silver', 'White', 'Yellow'],
            ],
            [
                'name' => 'Length',
                'values' => ['5ft', '6ft', '7ft', '8ft', '9ft', '10ft', '12ft'],
            ],
            [
                'name' => 'Power',
                'values' => ['Ultra Light', 'Light', 'Medium Light', 'Medium', 'Medium Heavy', 'Heavy', 'Extra Heavy'],
            ],
            [
                'name' => 'Action',
                'values' => ['Slow', 'Moderate', 'Fast', 'Extra Fast'],
            ],
            [
                'name' => 'Material',
                'values' => ['Graphite', 'Fiberglass', 'Carbon Fiber', 'Composite', 'Aluminum', 'Stainless Steel'],
            ],
            [
                'name' => 'Size',
                'values' => ['Small', 'Medium', 'Large', 'X-Large', 'XX-Large'],
            ],
            [
                'name' => 'Weight',
                'values' => ['1/8oz', '1/4oz', '3/8oz', '1/2oz', '3/4oz', '1oz', '1.5oz', '2oz'],
            ],
        ];

        foreach ($attributes as $attributeData) {
            $attribute = Attribute::create([
                'name' => $attributeData['name'],
            ]);

            foreach ($attributeData['values'] as $value) {
                AttributeValue::create([
                    'attribute_id' => $attribute->id,
                    'value' => $value,
                ]);
            }
        }
    }
}