<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Call all your custom seeders here
        $this->call([
            FeaturesSeeder::class,
            RoomTypesTableSeeder::class,
            RoomRatesTableSeeder::class,
            RoomFeatureSeeder::class,
            RoomImageSeeder::class,
        ]);
    }
}
