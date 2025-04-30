<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model; 
use Illuminate\Support\Facades\DB; 
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;
use App\Models\MongoRoomType;
use Illuminate\Support\Facades\Log;

class Rooms extends Model
{
    protected $connection = 'mongodb'; // Use MongoDB connection

    protected $collection = 'rooms'; // Collection name

    protected $fillable = [
        'room_no',
        'room_type',
        'status',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'room_no' => 'integer',
        'room_type' => 'string', // will still be stored as ObjectId, unless you cast manually
        'status' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    protected $dates = ['created_at', 'updated_at'];

    // 🔗 Define relationship to MongoRoomTypes
    public function type()
    {
        return $this->belongsTo(MongoRoomType::class, 'room_type', '_id');
    }

    public static function getTypeId($id) {
        $client = DB::connection('mongodb')->getMongoClient();
        $database = $client->bembang_hotel;
        $collection = $database->rooms;
    
        Log::info("Attempting to find room with ID: {$id}");
    
        try {
            // Try to convert string ID to ObjectId
            $objectId = new \MongoDB\BSON\ObjectId($id);
            $document = $collection->findOne(['_id' => $objectId]);
            
            if ($document) {
                Log::debug("Found room using ObjectId conversion", ['id' => $id, 'document' => $document]);
            } else {
                Log::debug("No room found using ObjectId conversion", ['id' => $id]);
            }
        } catch (\Exception $e) {
            Log::warning("Invalid ObjectId format, trying as string", [
                'id' => $id,
                'error' => $e->getMessage()
            ]);
            
            // If ID isn't valid ObjectId format, try as string
            $document = $collection->findOne(['_id' => $id]);
            
            if ($document) {
                Log::debug("Found room using string ID", ['id' => $id, 'document' => $document]);
            } else {
                Log::debug("No room found using string ID", ['id' => $id]);
            }
        }
    
        if (!$document) {
            Log::warning("No room document found for ID", ['id' => $id]);
            return null;
        }
    
        // Return the room_type field if it exists
        $room = iterator_to_array($document);
        $roomType = $room['room_type'] ?? null;
        
        Log::info("Returning room_type for room ID {$id}", [
            'room_type' => $roomType,
            'room_found' => !empty($room)
        ]);
        
        return $roomType;
    }
    public static function getSpecificRoom($id) {
        $client = DB::connection('mongodb')->getMongoClient();
        $database = $client->bembang_hotel;
        $collection = $database->rooms;
    
        try {
            // Try to convert string ID to ObjectId
            $objectId = new \MongoDB\BSON\ObjectId($id);
            $document = $collection->findOne(['_id' => $objectId]);
        } catch (\Exception $e) {
            // If ID isn't valid ObjectId format, try as string
            $document = $collection->findOne(['_id' => $id]);
        }
    
        if (!$document) {
            return null;
        }
    
        // Convert MongoDB document to array
        $room = iterator_to_array($document);
        
        // Get room type details if room_type exists
        if (isset($room['room_type'])) {
            $roomType = MongoRoomType::findSpecificRoom($room['room_type']);
            $room['room_type_details'] = $roomType;
        }
    
        return $room;
    }
    public static function getAll()
    {
        try {
            // Get the MongoDB client
            $client = DB::connection('mongodb')->getMongoClient();

            // Access the database and collection
            $database = $client->bembang_hotel;  // Your DB name
            $collection = $database->rooms;  // Collection name

            // Fetch all documents from the collection
            $documents = $collection->find()->toArray();  // Use the find method for all documents

            // Return the documents as a collection or array
            return collect($documents);
        } catch (\Exception $e) {
            // Handle errors and log the issue
            Log::error("MongoDB query error: " . $e->getMessage());
            return collect([]);  // Return empty collection in case of error
        }
    }
    public static function countAvailability() {
        $client = DB::connection('mongodb')->getMongoClient();
        $database = $client->bembang_hotel;
        $collection = $database->rooms;
        $rooms = $collection;
        
        $counts = [
            'available' => 0,
            'occupied' => 0,
            'maintenance' => 0,
            'cleaning' => 0,
            'other' => 0
        ];
    
        foreach ($rooms as $room) {
            $status = strtolower($room->status ?? 'other');
            
            if (isset($counts[$status])) {
                $counts[$status]++;
            } else {
                $counts['other']++;
            }
        }
    
        return $counts;
    }
}
