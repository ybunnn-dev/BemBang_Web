<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;  // Correct base class for mongodb/laravel-mongodb

class RoomType extends Model
{
    protected $collection = 'room_types';
    
    protected $primaryKey = '_id'; // MongoDB uses '_id' as default primary key

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
}