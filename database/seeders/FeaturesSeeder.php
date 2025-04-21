<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FeaturesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('features')->insert([
            [
                'feature_name' => 'Air Conditioning',
                'feature_icon' => 'images/icons/air_conditioning.png',
                'status' => 'active',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'feature_name' => 'Free Wi-Fi',
                'feature_icon' => 'images/icons/free_wifi.png',
                'status' => 'active',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'feature_name' => 'Flat Screen TV',
                'feature_icon' => 'images/icons/flat_screen_tv.png',
                'status' => 'active',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'feature_name' => 'Mini Bar',
                'feature_icon' => 'images/icons/mini_bar.png',
                'status' => 'active',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'feature_name' => 'Balcony',
                'feature_icon' => 'images/icons/balcony.png',
                'status' => 'active',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'feature_name' => 'Room Service',
                'feature_icon' => 'images/icons/room_service.png',
                'status' => 'active',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'feature_name' => 'Safety Deposit Box',
                'feature_icon' => 'images/icons/safety_deposit_box.png',
                'status' => 'active',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'feature_name' => 'Gym',
                'feature_icon' => 'images/icons/gym.png',
                'status' => 'active',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'feature_name' => 'Swimming Pool',
                'feature_icon' => 'images/icons/swimming_pool.png',
                'status' => 'active',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'feature_name' => 'Pet-Friendly',
                'feature_icon' => 'images/icons/pet_friendly.png',
                'status' => 'active',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
