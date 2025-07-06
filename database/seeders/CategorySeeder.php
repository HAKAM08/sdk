<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Fishing Rods',
                'description' => 'High-quality fishing rods for all types of fishing',
                'children' => [
                    ['name' => 'Spinning Rods', 'description' => 'Versatile rods for various fishing techniques'],
                    ['name' => 'Casting Rods', 'description' => 'Perfect for accurate casting and heavy lures'],
                    ['name' => 'Fly Rods', 'description' => 'Specialized rods for fly fishing'],
                    ['name' => 'Telescopic Rods', 'description' => 'Compact and portable fishing rods'],
                ],
            ],
            [
                'name' => 'Fishing Reels',
                'description' => 'Premium fishing reels for all fishing styles',
                'children' => [
                    ['name' => 'Spinning Reels', 'description' => 'Versatile reels for various fishing techniques'],
                    ['name' => 'Baitcasting Reels', 'description' => 'For precise casting and control'],
                    ['name' => 'Fly Reels', 'description' => 'Specialized reels for fly fishing'],
                    ['name' => 'Conventional Reels', 'description' => 'Heavy-duty reels for big game fishing'],
                ],
            ],
            [
                'name' => 'Fishing Lines',
                'description' => 'High-quality fishing lines for all conditions',
                'children' => [
                    ['name' => 'Monofilament Lines', 'description' => 'Versatile and easy to use'],
                    ['name' => 'Fluorocarbon Lines', 'description' => 'Nearly invisible underwater'],
                    ['name' => 'Braided Lines', 'description' => 'Strong and durable with no stretch'],
                    ['name' => 'Fly Lines', 'description' => 'Specialized lines for fly fishing'],
                ],
            ],
            [
                'name' => 'Lures & Baits',
                'description' => 'Effective lures and baits for all species',
                'children' => [
                    ['name' => 'Soft Baits', 'description' => 'Lifelike soft plastic lures'],
                    ['name' => 'Hard Baits', 'description' => 'Durable and effective hard lures'],
                    ['name' => 'Spinnerbaits', 'description' => 'Versatile lures with spinning blades'],
                    ['name' => 'Flies', 'description' => 'Artificial flies for fly fishing'],
                ],
            ],
            [
                'name' => 'Fishing Accessories',
                'description' => 'Essential accessories for fishing success',
                'children' => [
                    ['name' => 'Hooks', 'description' => 'Various hooks for different fishing techniques'],
                    ['name' => 'Sinkers & Weights', 'description' => 'Essential for proper bait presentation'],
                    ['name' => 'Swivels & Snaps', 'description' => 'Prevent line twist and allow quick lure changes'],
                    ['name' => 'Fishing Tools', 'description' => 'Pliers, hook removers, and other essential tools'],
                ],
            ],
            [
                'name' => 'Fishing Clothing',
                'description' => 'Specialized clothing for fishing comfort',
                'children' => [
                    ['name' => 'Fishing Vests', 'description' => 'Practical vests with multiple pockets'],
                    ['name' => 'Fishing Hats', 'description' => 'Protection from sun and elements'],
                    ['name' => 'Fishing Gloves', 'description' => 'Protection and grip for handling fish'],
                    ['name' => 'Waders', 'description' => 'Waterproof gear for stream and river fishing'],
                ],
            ],
        ];

        foreach ($categories as $categoryData) {
            $this->createCategory($categoryData);
        }
    }

    /**
     * Create a category and its children.
     */
    private function createCategory(array $data, ?int $parentId = null): void
    {
        $children = $data['children'] ?? [];
        unset($data['children']);

        $data['slug'] = Str::slug($data['name']);
        $data['parent_id'] = $parentId;

        $category = Category::create($data);

        foreach ($children as $childData) {
            $this->createCategory($childData, $category->id);
        }
    }
}