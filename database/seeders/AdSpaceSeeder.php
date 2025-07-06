<?php

namespace Database\Seeders;

use App\Models\AdSpace;
use Illuminate\Database\Seeder;

class AdSpaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample ad spaces
        AdSpace::create([
            'title' => 'Special Discount',
            'description' => 'Get 20% off on all fishing rods',
            'image' => '/images/adspaces/ad1.jpg',
            'link' => '/products?category=fishing-rods',
            'position' => 'left',
            'active' => true,
        ]);

        AdSpace::create([
            'title' => 'New Arrivals',
            'description' => 'Check out our latest fishing accessories',
            'image' => '/images/adspaces/ad2.jpg',
            'link' => '/products?sort=newest',
            'position' => 'right',
            'active' => true,
        ]);
    }
}