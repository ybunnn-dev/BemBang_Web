<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model; 
use Illuminate\Support\Facades\DB; 
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;
use App\Models\MongoRoomType;

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
}
