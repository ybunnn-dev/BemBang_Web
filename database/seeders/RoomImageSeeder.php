<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RoomImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('room_images')->insert([
            // Bembang Standard Room Images
            [
                'room_type_id' => 1, 
                'file_path' => 'images/rooms/standard.jpg', 
                'main_indicator' => 1, 
                'status' => 'active', // Added status
                'created_at' => Carbon::now(), 
                'updated_at' => Carbon::now(),
            ],
            [
                'room_type_id' => 1, 
                'file_path' => 'images/rooms/standard.jpg', 
                'main_indicator' => 0, 
                'status' => 'active', // Added status
                'created_at' => Carbon::now(), 
                'updated_at' => Carbon::now(),
            ],
            [
                'room_type_id' => 1, 
                'file_path' => 'images/rooms/bembang_standard_2.jpg', 
                'main_indicator' => 0, 
                'status' => 'active', // Added status
                'created_at' => Carbon::now(), 
                'updated_at' => Carbon::now(),
            ],

            // Bembang Twin Room Images
            [
                'room_type_id' => 2, 
                'file_path' => 'images/rooms/twin.jpg', 
                'main_indicator' => 1, 
                'status' => 'active', // Added status
                'created_at' => Carbon::now(), 
                'updated_at' => Carbon::now(),
            ],
            [
                'room_type_id' => 2, 
                'file_path' => 'images/rooms/twin.jpg', 
                'main_indicator' => 0, 
                'status' => 'active', // Added status
                'created_at' => Carbon::now(), 
                'updated_at' => Carbon::now(),
            ],
            [
                'room_type_id' => 2, 
                'file_path' => 'images/rooms/twin.jpg', 
                'main_indicator' => 0, 
                'status' => 'active', // Added status
                'created_at' => Carbon::now(), 
                'updated_at' => Carbon::now(),
            ],

            // Bembang Family Room Images
            [
                'room_type_id' => 3, 
                'file_path' => 'images/rooms/family.jpg', 
                'main_indicator' => 1, 
                'status' => 'active', // Added status
                'created_at' => Carbon::now(), 
                'updated_at' => Carbon::now(),
            ],
            [
                'room_type_id' => 3, 
                'file_path' => 'images/rooms/family.jpg', 
                'main_indicator' => 0, 
                'status' => 'active', // Added status
                'created_at' => Carbon::now(), 
                'updated_at' => Carbon::now(),
            ],
            [
                'room_type_id' => 3, 
                'file_path' => 'images/rooms/bembang_family_2.jpg', 
                'main_indicator' => 0, 
                'status' => 'active', // Added status
                'created_at' => Carbon::now(), 
                'updated_at' => Carbon::now(),
            ],

            // Bembang Deluxe Room Images
            [
                'room_type_id' => 4, 
                'file_path' => 'images/deluxe.jpg', 
                'main_indicator' => 1, 
                'status' => 'active', // Added status
                'created_at' => Carbon::now(), 
                'updated_at' => Carbon::now(),
            ],
            [
                'room_type_id' => 4, 
                'file_path' => 'images/rooms/bembang_deluxe_1.jpg', 
                'main_indicator' => 0, 
                'status' => 'active', // Added status
                'created_at' => Carbon::now(), 
                'updated_at' => Carbon::now(),
            ],
            [
                'room_type_id' => 4, 
                'file_path' => 'images/rooms/bembang_deluxe_2.jpg', 
                'main_indicator' => 0, 
                'status' => 'active', // Added status
                'created_at' => Carbon::now(), 
                'updated_at' => Carbon::now(),
            ],

            // Bembang Suite Room Images
            [
                'room_type_id' => 5, 
                'file_path' => 'images/rooms/suite.jpg', 
                'main_indicator' => 1, 
                'status' => 'active', // Added status
                'created_at' => Carbon::now(), 
                'updated_at' => Carbon::now(),
            ],
            [
                'room_type_id' => 5, 
                'file_path' => 'images/rooms/bembang_suite_1.jpg', 
                'main_indicator' => 0, 
                'status' => 'active', // Added status
                'created_at' => Carbon::now(), 
                'updated_at' => Carbon::now(),
            ],
            [
                'room_type_id' => 5, 
                'file_path' => 'images/rooms/bembang_suite_2.jpg', 
                'main_indicator' => 0, 
                'status' => 'active', // Added status
                'created_at' => Carbon::now(), 
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
