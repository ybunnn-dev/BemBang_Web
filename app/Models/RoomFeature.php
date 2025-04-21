<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomFeature extends Model
{
    protected $table = 'room_features';

    protected $fillable = [
        'room_type_id',
        'feature_id',
        'feature_status',
    ];

    public $timestamps = true;

    // Optional: define relationships if applicable
    public function roomType()
    {
        return $this->belongsTo(RoomType::class, 'room_type_id');
    }

    public function feature()
    {
        return $this->belongsTo(Feature::class, 'feature_id');
    }
}
