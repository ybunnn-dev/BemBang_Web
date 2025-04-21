<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomTypeWithMainImage extends Model
{
    protected $table = 'room_types_with_main_image';
    protected $primaryKey = 'room_type_id'; // This is the actual PK in your view

    public $incrementing = false; // It's a view, not an auto-incrementing table
    public $timestamps = false;   // Views typically don’t need timestamps
}
