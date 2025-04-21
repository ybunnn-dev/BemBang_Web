<?php

namespace App\Models;

use MongoDB\Laravel\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use MongoDB\Laravel\Eloquent\Model; 

class User extends Authenticatable
{
    protected $connection = 'mongodb';
    protected $collection = 'users';

    use Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'firstName',
        'lastName',
        'email',
        'password',
        'otp',
        'otpExpires',
        'isVerified',
        'address',
        'gender',
        'mobileNumber',
        'profilePic',
        'status',
        'role',
        'createdAt'
    ];

    protected $casts = [
        'isVerified' => 'boolean',
        'otpExpires' => 'datetime',
        'createdAt' => 'datetime',
    ];

    public $timestamps = false; // Set to true if you're using created_at / updated_at columns

    protected $primaryKey = '_id'; // Optional: if you're using MongoDB-style IDs
    public $incrementing = false;
    protected $keyType = 'string';
}