<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RoomTypesTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('room_types')->insert([
            [
                'type_name' => 'Peter Standard',
                'description' => 'A standard room with basic amenities.',
                'guest_num' => 2,  // Added number of guests
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'type_name' => 'Peter Twin',
                'description' => 'A twin room with two single beds.',
                'guest_num' => 2,  // Added number of guests
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'type_name' => 'Peter Family',
                'description' => 'Spacious family room with multiple beds.',
                'guest_num' => 4,  // Added number of guests
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'type_name' => 'Peter Deluxe',
                'description' => 'Deluxe room with premium features and view.',
                'guest_num' => 3,  // Added number of guests
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'type_name' => 'Peter Suite',
                'description' => 'Luxury suite with living area and top-tier amenities.',
                'guest_num' => 5,  // Added number of guests
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
