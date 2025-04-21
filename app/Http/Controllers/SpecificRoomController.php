<?php

namespace App\Http\Controllers;

use App\Models\Rooms;
use App\Models\MongoRoomType;
use App\Models\Features;
use MongoDB\BSON\ObjectId;
use Illuminate\Support\Facades\Log;


class SpecificRoomController extends Controller
{
    public function show($id)
    {
        try {
            // Convert to MongoDB ObjectId
            $objectId = new ObjectId($id);

            // Find the specific room
            $room = Rooms::find($objectId);

            // Handle case where room is not found
            if (!$room) {
                abort(404, 'Room not found');
            }

            // Get the corresponding room type
            $roomType = MongoRoomType::findSpecificRoom($room->room_type);

            // Convert BSONArray to array - using getArrayCopy() or casting
            $featureIds = $roomType->room_features instanceof \MongoDB\Model\BSONArray 
                ? $roomType->room_features->getArrayCopy() 
                : (array)$roomType->room_features;

            // Query features - ensure Features model uses MongoDB Eloquent
            $features = Features::whereIn('_id', $featureIds)->get();
            Log::info('Room Features:', $features->toArray());

            // Add features to roomType
            $roomType->features = $features;

            // Combine data
            $roomWithType = (object)[
                'id' => $room->_id,
                'room_no' => $room->room_no,
                'status' => $room->status,
                'room_type' => $roomType,
                'created_at' => $room->created_at,
                'updated_at' => $room->updated_at,
            ];

            return view('management.specific_room', [
                'room' => $roomWithType, 'features' => $features
            ]);

        } catch (\Exception $e) {
            Log::error('Error in SpecificRoomController: ' . $e->getMessage());
            abort(500, 'An error occurred while processing your request');
        }
    }
}