<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Rooms;
use App\Models\MongoRoomType;
use Illuminate\Support\Facades\Log;

class CheckinController extends Controller
{
    public function getCheckInDetails()
    {
        try {
            // Fetch rooms with their associated room types
            $rooms = Rooms::with('type')->get();

            // Fetch all room types
            $all_types = MongoRoomType::getAllMongoRoomTypes();

            // Fetch transaction schedules
            $schedules = Transaction::getTransactionSchedules();

            // Log the fetched data for debugging
            Log::debug('Check-in details fetched', [
                'rooms_count' => $rooms->count(),
                'types_count' => $all_types->count(),
                'schedules_count' => count($schedules),
            ]);

            // Return JSON response
            return response()->json([
                'rooms' => $rooms,
                'all_types' => $all_types,
                'schedules' => $schedules,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to fetch check-in details', [
                'error' => $e->getMessage(),
            ]);
            return response()->json(['error' => 'Failed to fetch check-in details'], 500);
        }
    }
}