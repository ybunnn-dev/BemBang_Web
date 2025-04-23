<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;  
use Illuminate\Support\Facades\DB;  // To access DB facade for direct MongoDB queries
use Illuminate\Support\Str; // Add this import
use MongoDB\BSON\ObjectId;
use Illuminate\Support\Facades\Log;
use MongoDB\Laravel\Eloquent\Casts\ObjectId as CastsObjectId;

class Membership extends Model
{
    /**
     * The collection associated with the model.
     *
     * @var string
     */
    protected $collection = 'memberships';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = '_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'membership_name',
        'membership_level',
        'check_in_threshold',
        'check_in_points',
        'booking_points',
        'reservation_points',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'membership_level' => 'integer',
        'check_in_threshold' => 'integer',
        'check_in_points' => 'integer',
        'booking_points' => 'integer',
        'reservation_points' => 'integer',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at'];

    public static function getAll(){
        try {
            // Get the MongoDB client
            $client = DB::connection('mongodb')->getMongoClient();

            // Access the database and collection
            $database = $client->bembang_hotel;  // Your DB name
            $collection = $database->membership;  // Collection name

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
    public static function getSpecificMembership($id)
    {
        try {
           
            $memId = new \MongoDB\BSON\ObjectId($id);
            $collection = DB::getMongoDB()->selectCollection('membership');
            $document = $collection->findOne(['_id' => $memId]);
            
            return collect([$document]);
            
        } catch (\Exception $e) {
            Log::error("MongoDB query error: " . $e->getMessage(), [
                'id' => $id,
                'error' => $e->getTraceAsString()
            ]);
            return collect([]);
        }
    }


}