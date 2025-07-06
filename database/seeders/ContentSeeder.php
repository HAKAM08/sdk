<?php

namespace Database\Seeders;

use App\Models\Content;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('is_admin', true)->first();

        $contents = [
            [
                'title' => 'Beginner\'s Guide to Fishing Rods',
                'content' => '<h2>Understanding Fishing Rods</h2>
<p>Choosing the right fishing rod is crucial for your success on the water. This guide will help you understand the different types of fishing rods and how to select the best one for your needs.</p>

<h3>Types of Fishing Rods</h3>
<p><strong>Spinning Rods:</strong> Versatile and easy to use, making them perfect for beginners. They work well with lighter lures and baits.</p>
<p><strong>Casting Rods:</strong> Designed for accuracy and power, ideal for experienced anglers targeting larger fish.</p>
<p><strong>Fly Rods:</strong> Specialized rods for fly fishing, designed to cast lightweight flies using the weight of the line.</p>
<p><strong>Telescopic Rods:</strong> Collapsible rods that are convenient for travel and storage.</p>

<h3>Key Factors to Consider</h3>
<p><strong>Length:</strong> Longer rods (7-10 feet) cast farther, while shorter rods (5-6.5 feet) offer more accuracy and power.</p>
<p><strong>Power:</strong> Refers to the rod\'s strength or lifting power, ranging from ultra-light to extra-heavy.</p>
<p><strong>Action:</strong> Describes where the rod bends when pressure is applied. Fast action rods bend near the tip, while slow action rods bend throughout the length.</p>
<p><strong>Material:</strong> Most modern rods are made from graphite, fiberglass, or a composite of both. Graphite is lightweight and sensitive, while fiberglass is more durable and forgiving.</p>

<h3>Matching Rod to Fishing Style</h3>
<p>For freshwater fishing in lakes and ponds, a medium power, fast action spinning rod around 6-7 feet is versatile.</p>
<p>For stream fishing, a shorter rod (5-6 feet) allows for more precise casts around obstacles.</p>
<p>For saltwater fishing from shore, longer rods (8-10 feet) help cast beyond the breaking waves.</p>

<h3>Maintenance Tips</h3>
<p>Rinse your rod with fresh water after each use, especially after saltwater fishing.</p>
<p>Store your rod in a cool, dry place, preferably in a rod case to protect it from damage.</p>
<p>Regularly check the guides for cracks or rough spots that could damage your line.</p>',
                'type' => 'guide',
                'published' => true,
                'related_products' => ['Pro Angler Spinning Rod'],
            ],
            [
                'title' => 'Seasonal Fishing Tips: Summer Bass Tactics',
                'content' => '<h2>Summer Bass Fishing Strategies</h2>
<p>As water temperatures rise during summer months, bass fishing requires adjusted tactics. This guide provides effective strategies for catching bass during the hot summer season.</p>

<h3>Understanding Summer Bass Behavior</h3>
<p>During summer, bass often seek cooler, oxygen-rich water. They typically move to deeper areas during the day and may feed in shallower water during early morning and evening hours.</p>

<h3>Early Morning Tactics</h3>
<p>The first few hours after sunrise offer prime fishing opportunities. Bass often feed actively in shallower water during this time.</p>
<p><strong>Recommended lures:</strong> Topwater baits like poppers and walking baits can be extremely effective during this period.</p>
<p><strong>Target areas:</strong> Focus on shoreline cover, weed edges, and shallow points.</p>

<h3>Midday Strategies</h3>
<p>As the sun rises higher, bass typically retreat to deeper, cooler water.</p>
<p><strong>Recommended lures:</strong> Deep-diving crankbaits, heavy jigs, and Carolina rigs work well for reaching bass in deeper water.</p>
<p><strong>Target areas:</strong> Focus on deep weed edges, drop-offs, underwater humps, and creek channels.</p>

<h3>Evening Approaches</h3>
<p>As temperatures cool in the evening, bass often return to shallower areas to feed.</p>
<p><strong>Recommended lures:</strong> Spinnerbaits, swimbaits, and soft plastic worms are effective during this transition period.</p>
<p><strong>Target areas:</strong> Focus on points, weed edges, and areas where deeper water meets shallow feeding zones.</p>

<h3>Night Fishing</h3>
<p>Summer nights can provide excellent bass fishing opportunities as water temperatures cool and bass become more active.</p>
<p><strong>Recommended lures:</strong> Dark-colored soft plastics, large slow-moving swimbaits, and noisy topwater lures.</p>
<p><strong>Target areas:</strong> Focus on shallow flats near deep water, points, and weed edges.</p>

<h3>Equipment Considerations</h3>
<p>Use braided line for fishing in heavy cover and when using topwater lures.</p>
<p>Fluorocarbon line works well for deep water tactics due to its low visibility and sinking properties.</p>
<p>Ensure your reels have smooth drag systems to handle the potentially larger bass that become more active during summer months.</p>',
                'type' => 'seasonal',
                'published' => true,
                'related_products' => ['Tournament Pro Baitcasting Reel', 'Predator Soft Plastic Swimbaits (5-pack)'],
            ],
            [
                'title' => 'Quick Tip: Proper Line Spooling Technique',
                'content' => '<h2>How to Properly Spool Fishing Line</h2>
<p>Correctly spooling your fishing reel is essential for optimal performance. This quick guide will help you avoid common mistakes and ensure your line lays evenly on the spool.</p>

<h3>Step-by-Step Spooling Process</h3>
<ol>
<li><strong>Prepare your equipment:</strong> Place your new line spool on a pencil or line spooler so it can rotate freely.</li>
<li><strong>Check line direction:</strong> Ensure the line comes off the spool in the same direction it will go onto your reel. This reduces line memory and twisting.</li>
<li><strong>Attach the line:</strong> Tie the line to your reel spool using an arbor knot.</li>
<li><strong>Apply tension:</strong> Hold the line between your fingers with light pressure as you reel to ensure proper tension.</li>
<li><strong>Fill appropriately:</strong> Fill the spool to about 1/8 inch from the rim (for spinning reels) or to the manufacturer\'s recommended level (for baitcasting reels).</li>
</ol>

<h3>Common Mistakes to Avoid</h3>
<p><strong>Overfilling:</strong> Too much line can cause casting problems and line tangles.</p>
<p><strong>Insufficient tension:</strong> Line that\'s too loose on the spool will create problems during casting.</p>
<p><strong>Wrong direction:</strong> Line should come off the supply spool in the same direction it will be wound onto the reel.</p>

<h3>Line Type Considerations</h3>
<p><strong>Monofilament:</strong> Soak in warm water for 15 minutes before spooling to reduce memory.</p>
<p><strong>Fluorocarbon:</strong> Apply slightly more tension when spooling to prevent loosening.</p>
<p><strong>Braided line:</strong> Use a monofilament backing or electrical tape on the spool to prevent slippage.</p>',
                'type' => 'tip',
                'published' => true,
                'related_products' => ['SuperCast Braided Fishing Line'],
            ],
        ];

        foreach ($contents as $contentData) {
            $this->createContent($contentData, $admin->id);
        }
    }

    /**
     * Create content with related products.
     */
    private function createContent(array $data, int $userId): void
    {
        $relatedProducts = $data['related_products'] ?? [];
        unset($data['related_products']);

        $data['slug'] = Str::slug($data['title']);
        $data['user_id'] = $userId;

        $content = Content::create($data);

        // Attach related products
        $productIds = [];
        foreach ($relatedProducts as $productName) {
            $product = Product::where('name', $productName)->first();
            if ($product) {
                $productIds[] = $product->id;
            }
        }
        $content->relatedProducts()->attach($productIds);
    }
}