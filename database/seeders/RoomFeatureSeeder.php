<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoomFeatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Example data for the room_features table
        DB::table('room_features')->insert([
            // Bembang Standard Room Features
            [
                'room_type_id' => 1, // Assuming room type 1 is Bembang Standard
                'feature_id' => 1,   // Assuming feature 1 exists
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'room_type_id' => 1, // Bembang Standard Room
                'feature_id' => 2,   // Assuming feature 2 exists
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Bembang Twin Room Features
            [
                'room_type_id' => 2, // Bembang Twin Room
                'feature_id' => 1,   // Assuming feature 1 exists
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'room_type_id' => 2, // Bembang Twin Room
                'feature_id' => 3,   // Assuming feature 3 exists
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Bembang Family Room Features
            [
                'room_type_id' => 3, // Bembang Family Room
                'feature_id' => 4,   // Assuming feature 4 exists
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'room_type_id' => 3, // Bembang Family Room
                'feature_id' => 5,   // Assuming feature 5 exists
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Bembang Deluxe Room Features
            [
                'room_type_id' => 4, // Bembang Deluxe Room
                'feature_id' => 2,   // Assuming feature 2 exists
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'room_type_id' => 4, // Bembang Deluxe Room
                'feature_id' => 3,   // Assuming feature 3 exists
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Bembang Suite Room Features
            [
                'room_type_id' => 5, // Bembang Suite Room
                'feature_id' => 1,   // Assuming feature 1 exists
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'room_type_id' => 5, // Bembang Suite Room
                'feature_id' => 4,   // Assuming feature 4 exists
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
