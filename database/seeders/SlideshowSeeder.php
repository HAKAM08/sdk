<?php

namespace Database\Seeders;

use App\Models\Slideshow;
use Illuminate\Database\Seeder;

class SlideshowSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample slideshows
        Slideshow::create([
            'title' => 'Premium Fishing Tackle for Every Angler',
            'description' => 'Discover top-quality fishing gear designed for performance and reliability.',
            'image' => '/images/slideshows/slide1.jpg',
            'link' => '/products',
            'order' => 1,
            'active' => true,
        ]);

        Slideshow::create([
            'title' => 'New Season Collection',
            'description' => 'Explore our latest fishing gear for the upcoming season.',
            'image' => '/images/slideshows/slide2.jpg',
            'link' => '/categories/seasonal',
            'order' => 2,
            'active' => true,
        ]);

        Slideshow::create([
            'title' => 'Expert Fishing Tips & Guides',
            'description' => 'Learn from the pros and improve your fishing skills.',
            'image' => '/images/slideshows/slide3.jpg',
            'link' => '/content',
            'order' => 3,
            'active' => true,
        ]);
    }
}