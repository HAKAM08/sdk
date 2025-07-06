<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
            CategorySeeder::class,
            AttributeSeeder::class,
            ProductSeeder::class,
            ContentSeeder::class,
            OrderSeeder::class,
            SlideshowSeeder::class,
            AdSpaceSeeder::class,
            // TranslationSeeder removed - English only site
        ]);
    }
}
