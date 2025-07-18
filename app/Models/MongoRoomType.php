<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;  // Correct base class for mongodb/laravel-mongodb
use Illuminate\Support\Facades\DB;  // To access DB facade for direct MongoDB queries
use Illuminate\Support\Str; // Add this import
use MongoDB\BSON\ObjectId;
use Illuminate\Support\Facades\Log;

class MongoRoomType extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'room_types';

    protected $fillable = [
        'type_name',
        'description',
        'guest_num',
        'status',
        'rates',
        'images',
        'room_features'
    ];


    protected $casts = [
        'rates' => 'array',
        'images' => 'array',
        'room_features' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Fetch all Mongo Room Types using raw MongoDB query.
     * @return \Illuminate\Support\Collection
     */
    public static function getAllMongoRoomTypes()
    {
        try {
            // Get the MongoDB client
            $client = DB::connection('mongodb')->getMongoClient();

            // Access the database and collection
            $database = $client->bembang_hotel;  // Your DB name
            $collection = $database->room_types;  // Collection name

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
    public static function findSpecificRoomWithFeature($id)
    {
        try {
            $client = DB::connection('mongodb')->getMongoClient();
            $database = $client->bembang_hotel;
            $collection = $database->room_types;
            $featureCollection = $database->features;
            
            // Convert string ID to ObjectId if needed
            try {
                $objectId = new ObjectId($id);
                $document = $collection->findOne(['_id' => $objectId]);
            } catch (\Exception $e) {
                $document = $collection->findOne(['_id' => $id]);
            }
            
            if (!$document) {
                return null;
            }
            
            // Convert to array for easier manipulation
            $roomType = (array)$document;
            
            // Get full feature details if features exist
            if (!empty($roomType['room_features'])) {
                // Convert BSONArray to PHP array
                $featureIds = [];
                foreach ($roomType['room_features'] as $featureId) {
                    $featureIds[] = $featureId instanceof ObjectId ? $featureId : new ObjectId($featureId);
                }
                
                $featuresCursor = $featureCollection->find([
                    '_id' => ['$in' => $featureIds]
                ]);
                
                $roomType['features'] = iterator_to_array($featuresCursor);
            }
            
            return (object)$roomType;
            
        } catch (\Exception $e) {
            Log::error("MongoDB find room with features error: " . $e->getMessage());
            return null;
        }
    }
    public static function findSpecificRoom($id)
    {
        try {
            Log::info("Attempting to find room type", ['room_type_id' => $id]);

            $client = DB::connection('mongodb')->getMongoClient();
            $database = $client->bembang_hotel;
            $collection = $database->room_types;
            
            // Convert string ID to ObjectId if needed
            try {
                $objectId = new ObjectId($id);
                Log::debug("Trying to find room type with ObjectId", ['objectId' => (string)$objectId]);
                
                $document = $collection->findOne(['_id' => $objectId]);
                
                if ($document) {
                    Log::debug("Found room type using ObjectId", [
                        'id' => $id,
                        'document' => json_encode($document)
                    ]);
                } else {
                    Log::warning("No room type found using ObjectId", ['objectId' => (string)$objectId]);
                }
            } catch (\Exception $e) {
                Log::warning("Invalid ObjectId format, trying as string", [
                    'id' => $id,
                    'error' => $e->getMessage()
                ]);
                
                // If ID isn't valid ObjectId format, try as string
                $document = $collection->findOne(['_id' => $id]);
                
                if ($document) {
                    Log::debug("Found room type using string ID", [
                        'id' => $id,
                        'document' => json_encode($document)
                    ]);
                } else {
                    Log::warning("No room type found using string ID", ['id' => $id]);
                }
            }
            
            $result = $document ? (object)$document : null;
            
            Log::info("Room type lookup result", [
                'id' => $id,
                'found' => $result !== null,
                'result' => $result ? json_encode($result) : null
            ]);
            
            return $result;
            
        } catch (\Exception $e) {
            Log::error("MongoDB find room error", [
                'id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return null;
        }
    }
    public static function updateRoomType($data){
        try {
            $client = DB::connection('mongodb')->getMongoClient();
            $database = $client->bembang_hotel;
            $collection = $database->room_types;
            
            // Convert string ID to ObjectId if needed
            try {
                $objectId = new ObjectId($data);
                $document = $collection->findOne(['_id' => $objectId]);
            } catch (\Exception $e) {
                // If ID isn't valid ObjectId format, try as string
                $document = $collection->findOne(['_id' => $id]);
            }
            
            return $document ? (object)$document : null;
            
        } catch (\Exception $e) {
            Log::error("MongoDB find room error: " . $e->getMessage());
            return null;
        }
    }
}
