<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model; 

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
}
