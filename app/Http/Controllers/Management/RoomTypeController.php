<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Models\RoomTypeWithMainImage;
use App\Models\RoomType;
use App\Models\MongoRoomType;
use App\Models\RoomFeature;
use App\Models\Features;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use MongoDB\BSON\ObjectId;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use MongoDB\Laravel\Eloquent\Model;


class RoomTypeController extends Controller{
    public function index()
    {
        $roomTypes = MongoRoomType::getAllMongoRoomTypes();  // Custom method from the MongoRoomType model

        // Return the data to the view
        return view('management.room-types', compact('roomTypes'));
    }
    public function show($id)
    {
        try {
            // Log the incoming ID string
            Log::info('Incoming ID', ['id' => $id]);
            
            // Get room type
            $roomType = MongoRoomType::findSpecificRoom($id);
            
            if (!$roomType) {
                Log::warning('Room not found by ID, falling back to first room');
                throw new \Exception('Room type not found');
            }
            
            // Get room features safely
            $featureIds = $roomType->room_features->getArrayCopy(); // Convert BSONArray to PHP array
    
            // Convert all feature IDs to strings for logging
            $featureStrings = array_map(function($id) {
                return (string)$id;
            }, $featureIds);
    
            Log::info('Room features', ['feature_ids' => $featureStrings]);
    
            // If you need to query features
            $features = Features::whereIn('_id', 
                array_map(function($id) {
                    return new ObjectId($id);
                }, $featureIds)
            )->get();
            
            $all_features = Features::getAllFeatures();
            return view('management.specific-type', [
                'roomType' => $roomType,
                'features' => $features,  // <-- Fixed: removed the incorrect $ prefix
                'all_features' => $all_features
            ]);
            
        } catch (\Exception $e) {
            Log::error('Room controller error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Error loading room: ' . $e->getMessage());
        }
    }
    public function update(Request $request)
    {
        Log::info('RoomType Update Request:', $request->all());

        try {
            $client = DB::connection('mongodb')->getMongoClient();
            $database = $client->bembang_hotel;
            $collection = $database->room_types;
            $id = new \MongoDB\BSON\ObjectId($request->input('id'));

            // Extract updates and changed fields
            $updates = $request->input('updates', []);
            $changedFields = $request->input('changed_fields', []);

            // Prepare update data
            $updateData = [];

            // Update only fields that are marked as changed
            if ($changedFields['name'] ?? false) {
                $updateData['type_name'] = $updates['type_name'] ?? null;
            }
            
            if ($changedFields['guest_num'] ?? false) {
                $updateData['guest_num'] = isset($updates['guest_num']) ? (int)$updates['guest_num'] : null;
            }
            
            if ($changedFields['description'] ?? false) {
                $updateData['description'] = $updates['description'] ?? null;
            }
            
            if ($changedFields['features'] ?? false) {
                $featureIds = $updates['features'] ?? [];
                if (is_array($featureIds)) {
                    $updateData['room_features'] = array_map(
                        function($id) {
                            return new \MongoDB\BSON\ObjectId($id);
                        },
                        array_filter($featureIds)
                    );
                }
            }

            // Only update if there are changes
            if (!empty($updateData)) {
                $result = $collection->updateOne(
                    ['_id' => $id],
                    ['$set' => $updateData]
                );

                Log::info('RoomType updated successfully:', [
                    'id' => (string)$id,
                    'changes' => $updateData,
                    'matched' => $result->getMatchedCount(),
                    'modified' => $result->getModifiedCount()
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Room Type updated successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Update failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Update failed: ' . $e->getMessage()
            ], 500);
        }
    }
    public function updateRates(Request $request){
        Log::info('RoomType Update Request:', $request->all());
    
        try {
            $client = DB::connection('mongodb')->getMongoClient();
            $database = $client->bembang_hotel;
            $collection = $database->room_types;
            $id = new \MongoDB\BSON\ObjectId($request->input('id'));
    
            // Prepare the rates update
            $updateData = [
                'rates' => [
                    'checkin_12h' => (int)$request->input('c12'),
                    'checkin_24h' => (int)$request->input('c24'),
                    'reservation_12h' => (int)$request->input('r12'),
                    'reservation_24h' => (int)$request->input('r24')
                ]
            ];
    
            // Also update the updated_at timestamp
            $updateData['updated_at'] = new \MongoDB\BSON\UTCDateTime();
    
            $result = $collection->updateOne(
                ['_id' => $id],
                ['$set' => $updateData]
            );
    
            Log::info('RoomType rates updated successfully:', [
                'id' => (string)$id,
                'changes' => $updateData,
                'matched' => $result->getMatchedCount(),
                'modified' => $result->getModifiedCount()
            ]);
    
            return response()->json([
                'success' => true,
                'message' => 'Room Type rates updated successfully'
            ]);
    
        } catch (\Exception $e) {
            Log::error('Update failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
    
            return response()->json([
                'success' => false,
                'message' => 'Update failed: ' . $e->getMessage()
            ], 500);
        }
    }
}
