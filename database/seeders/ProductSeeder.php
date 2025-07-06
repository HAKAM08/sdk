<?php

namespace Database\Seeders;

use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'Pro Angler Spinning Rod',
                'description' => '<p>The Pro Angler Spinning Rod is designed for serious anglers who demand performance and reliability. Crafted with high-modulus graphite blank, this rod offers exceptional sensitivity and strength while remaining lightweight. The premium cork handle provides comfort during long fishing sessions, and the stainless steel guides ensure smooth line flow for accurate casting.</p><p>Whether you\'re targeting bass in freshwater or redfish in the salt, this versatile spinning rod delivers the performance you need. Available in various lengths and power ratings to match your specific fishing requirements.</p>',
                'short_description' => 'High-performance spinning rod with graphite blank and premium cork handle',
                'price' => 129.99,
                'sale_price' => 109.99,
                'stock' => 25,
                'featured' => true,
                'categories' => ['Fishing Rods', 'Spinning Rods'],
                'attributes' => [
                    'Length' => ['7ft', '8ft'],
                    'Power' => ['Medium', 'Medium Heavy'],
                    'Action' => ['Fast'],
                    'Material' => ['Graphite'],
                    'Color' => ['Black', 'Blue'],
                ],
            ],
            [
                'name' => 'Tournament Pro Baitcasting Reel',
                'description' => '<p>The Tournament Pro Baitcasting Reel is engineered for precision and durability. Featuring a lightweight aluminum frame and side plates, this reel offers exceptional strength without adding unnecessary weight. The carbon fiber drag system provides smooth, consistent pressure throughout the fight, while the 10+1 stainless steel ball bearings ensure silky-smooth operation.</p><p>With a high-speed 7.3:1 gear ratio, this reel excels at techniques requiring fast retrieves, such as burning spinnerbaits or working topwater lures. The magnetic brake system allows for precise cast control, reducing backlash and improving accuracy.</p>',
                'short_description' => 'Professional-grade baitcasting reel with aluminum frame and carbon fiber drag',
                'price' => 199.99,
                'sale_price' => null,
                'stock' => 15,
                'featured' => true,
                'categories' => ['Fishing Reels', 'Baitcasting Reels'],
                'attributes' => [
                    'Material' => ['Aluminum'],
                    'Color' => ['Black', 'Silver'],
                ],
            ],
            [
                'name' => 'SuperCast Braided Fishing Line',
                'description' => '<p>SuperCast Braided Fishing Line represents the pinnacle of line technology. Constructed from ultra-high-molecular-weight polyethylene fibers, this line offers incredible strength-to-diameter ratio, allowing you to use thinner line without sacrificing strength. The tight weave creates a round profile that casts smoothly and quietly through the guides.</p><p>This braided line features enhanced abrasion resistance, making it ideal for fishing around structure. The minimal stretch provides exceptional sensitivity, allowing you to detect even the lightest bites. Available in various pound tests and colors to match your fishing conditions.</p>',
                'short_description' => 'Ultra-strong braided line with superior abrasion resistance',
                'price' => 29.99,
                'sale_price' => 24.99,
                'stock' => 50,
                'featured' => false,
                'categories' => ['Fishing Lines', 'Braided Lines'],
                'attributes' => [
                    'Color' => ['Green', 'Blue', 'White'],
                ],
            ],
            [
                'name' => 'Predator Soft Plastic Swimbaits (5-pack)',
                'description' => '<p>Predator Soft Plastic Swimbaits are designed to mimic the natural swimming action of baitfish, triggering aggressive strikes from predatory fish. Each swimbait features a realistic profile, detailed scale patterns, and a paddle tail that creates lifelike movement even at slow retrieve speeds.</p><p>Infused with fish-attracting scent and salt, these swimbaits appeal to multiple senses, increasing your chances of success. The premium soft plastic material offers the perfect balance of durability and action, allowing for multiple catches on a single bait. Each pack contains 5 swimbaits.</p>',
                'short_description' => 'Realistic swimbaits with paddle tail and fish-attracting scent',
                'price' => 12.99,
                'sale_price' => null,
                'stock' => 100,
                'featured' => false,
                'categories' => ['Lures & Baits', 'Soft Baits'],
                'attributes' => [
                    'Color' => ['White', 'Green', 'Yellow'],
                    'Weight' => ['1/4oz', '1/2oz'],
                ],
            ],
            [
                'name' => 'Pro Series Fishing Vest',
                'description' => '<p>The Pro Series Fishing Vest is designed for the angler who needs organization and accessibility on the water. Featuring 14 specialized pockets, this vest keeps all your essential gear within reach. The lightweight, quick-drying fabric ensures comfort in all conditions, while the mesh back provides ventilation on hot days.</p><p>Adjustable straps allow for a customized fit, and the reinforced D-rings provide convenient attachment points for nets, tools, and accessories. Whether you\'re wading a stream or fishing from a boat, this vest enhances your efficiency and enjoyment on the water.</p>',
                'short_description' => 'Multi-pocket fishing vest with quick-drying fabric and adjustable fit',
                'price' => 79.99,
                'sale_price' => 69.99,
                'stock' => 30,
                'featured' => false,
                'categories' => ['Fishing Clothing', 'Fishing Vests'],
                'attributes' => [
                    'Color' => ['Khaki', 'Green'],
                    'Size' => ['Medium', 'Large', 'X-Large'],
                ],
            ],
        ];

        foreach ($products as $productData) {
            $this->createProduct($productData);
        }
    }

    /**
     * Create a product with its categories and attributes.
     */
    private function createProduct(array $data): void
    {
        $categories = $data['categories'] ?? [];
        $attributesData = $data['attributes'] ?? [];
        
        unset($data['categories'], $data['attributes']);

        $data['slug'] = Str::slug($data['name']);
        $data['gallery_images'] = json_encode([]);

        $product = Product::create($data);

        // Attach categories
        $categoryIds = [];
        foreach ($categories as $categoryName) {
            $category = Category::where('name', $categoryName)->first();
            if ($category) {
                $categoryIds[] = $category->id;
            }
        }
        $product->categories()->attach($categoryIds);

        // Attach attribute values
        foreach ($attributesData as $attributeName => $values) {
            $attribute = Attribute::where('name', $attributeName)->first();
            if ($attribute) {
                $attributeValueIds = [];
                foreach ($values as $valueName) {
                    $attributeValue = AttributeValue::where('attribute_id', $attribute->id)
                        ->where('value', $valueName)
                        ->first();
                    if ($attributeValue) {
                        $attributeValueIds[] = $attributeValue->id;
                    }
                }
                $product->attributeValues()->attach($attributeValueIds);
            }
        }
    }
}