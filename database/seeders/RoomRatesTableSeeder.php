<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\RoomType;

class RoomRatesTableSeeder extends Seeder
{
    public function run(): void
    {
        $roomTypes = RoomType::all();

        foreach ($roomTypes as $roomType) {
            DB::table('room_rates')->insert([
                [
                    'room_type_id' => $roomType->room_type_id,
                    'hours' => 12,
                    'amount' => 1500.00, // Test value for 12 hours
                    'status' => 'active',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
                [
                    'room_type_id' => $roomType->room_type_id,
                    'hours' => 24,
                    'amount' => 2500.00, // Test value for 24 hours
                    'status' => 'active',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]
            ]);
        }
    }
}
