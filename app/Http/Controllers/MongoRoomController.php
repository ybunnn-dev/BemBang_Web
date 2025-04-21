<?php

namespace App\Http\Controllers;

use App\Models\Rooms;
use App\Models\MongoRoomType;
use Illuminate\Http\Request;
use MongoDB\BSON\ObjectId;

class MongoRoomController extends Controller
{
    public function view()
    {
        // Use the relationship we already defined
        $rooms = Rooms::with('type')->get();
        $all_types = MongoRoomType::getAllMongoRoomTypes();

        // Transform the data if needed
        $roomsWithTypes = $rooms->map(function ($room) {
            return (object) [
                'id' => $room->_id,
                'room_no' => $room->room_no,
                'status' => $room->status,
                'room_type' => MongoRoomType::findSpecificRoom($room->room_type), 
                'created_at' => $room->created_at,
                'updated_at' => $room->updated_at,
            ];
        });
        
        return view('management.manage-rooms', [
            'rooms' => $roomsWithTypes,
            'room_type' => $all_types
        ]);
    }


    public function addRoom(Request $request)
    {
        $validated = $request->validate([
            'room_type_id' => 'required|string',
            'room_no' => 'required|integer'
        ]);

        try {
            // Create new room
            $room = new Rooms();
            $room->_id = new ObjectId();
            $room->room_no = $validated['room_no'];
            $room->room_type = new ObjectId($validated['room_type_id']);
            $room->status = 'Available';
            $room->created_at = now();
            $room->updated_at = now();
            $room->save();

            return response()->json([
                'success' => true,
                'message' => 'Room added successfully',
                'room' => $room
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add room: ' . $e->getMessage()
            ], 500);
        }
    }
}
