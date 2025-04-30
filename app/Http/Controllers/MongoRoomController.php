<?php

namespace App\Http\Controllers;

use App\Models\Rooms;
use App\Models\MongoRoomType;
use App\Models\Transaction;
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

    public function frontdeskRoom()
    {
        // Use the relationship we already defined
        $rooms = Rooms::with('type')->get();
        $all_types = MongoRoomType::getAllMongoRoomTypes();
        

        // Transform the data if needed
        $roomsWithTypes = $rooms->map(function ($room) {
            $currentTransact = Transaction::getSpecificTransaction($room->_id);
            return (object) [
                'id' => $room->_id,
                'room_no' => $room->room_no,
                'status' => $room->status,
                'room_type' => MongoRoomType::findSpecificRoom($room->room_type), 
                'transaction' => $currentTransact,
                'created_at' => $room->created_at,
                'updated_at' => $room->updated_at,
            ];
        });
        
        return view('frontdesk.view_rooms', [
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
    public function redirectToRoomDetails($id)
    {
        // Get the specific room by ID with its type relationship
        $room = Rooms::with('type')->find($id);
        
        if (!$room) {
            // Handle case where room isn't found - maybe redirect back with error
            return redirect()->back()->with('error', 'Room not found');
        }

        // Get the room details with type and transaction info
        $roomDetails = (object) [
            'id' => $room->_id,
            'room_no' => $room->room_no,
            'status' => $room->status,
            'room_type' => MongoRoomType::findSpecificRoomWithFeature($room->room_type),
            'transaction' => Transaction::getSpecificTransaction($room->_id),
            'created_at' => $room->created_at,
            'updated_at' => $room->updated_at,
        ];

        // Get all room types (if needed for dropdowns or other UI elements)
        $all_types = MongoRoomType::getAllMongoRoomTypes();
        
        return view('frontdesk.specific_room', [
            'room' => $roomDetails,
            'room_type' => $all_types
        ]);
    }
}
