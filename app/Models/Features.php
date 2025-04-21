<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Log;

class Features extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'features';
    protected $primaryKey = '_id';
    
    protected $fillable = [
        'feature_name',
        'feature_icon',
        'status'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relationship to room types (if needed)
    public function roomTypes()
    {
        return $this->belongsToMany(RoomType::class, null, 'room_features', 'feature_id');
    }

    public static function getAllFeatures()
    {
        try {
            // Get the MongoDB client
            $client = DB::connection('mongodb')->getMongoClient();

            // Access the database and collection
            $database = $client->bembang_hotel;  // Your DB name
            $collection = $database->features;  // Collection name

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
    public static function getSpecificFeatures($id){
        
    }
}
