<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Transaction extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'transaction';
    protected $primaryKey = '_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'guest_id',
        'employee_id',
        'room_id',
        'voucher_id',
        'transaction_type',
        'payment',
        'stay_details',
        'current_status',
        'cancellation',
        'audit_log',
    ];

    protected $casts = [
        'payment' => 'array',
        'stay_details' => 'array',
        'audit_log' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function setGuestIdAttribute($value)
    {
        $this->attributes['guest_id'] = $value instanceof ObjectId ? (string) $value : $value;
    }

    public function setEmployeeIdAttribute($value)
    {
        $this->attributes['employee_id'] = $value instanceof ObjectId ? (string) $value : $value;
    }

    public function setRoomIdAttribute($value)
    {
        $this->attributes['room_id'] = $value instanceof ObjectId ? (string) $value : $value;
    }

    public function setVoucherIdAttribute($value)
    {
        $this->attributes['voucher_id'] = $value instanceof ObjectId ? (string) $value : $value;
    }

    public function guest()
    {
        return $this->belongsTo(Guest::class, 'guest_id', '_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', '_id');
    }

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id', '_id');
    }

    public function voucher()
    {
        return $this->belongsTo(Voucher::class, 'voucher_id', '_id');
    }

    public static function getTransactionSchedules()
    {
        try {
            // Get MongoDB client
            $client = DB::connection('mongodb')->getMongoClient();
            $database = $client->bembang_hotel;
            $collection = $database->transaction;
    
            // Fetch transactions with specific fields
            $transactions = $collection->find(
                [], // No filter, get all transactions
                [
                    'projection' => [
                        '_id' => 1,
                        'room_id' => 1,
                        'stay_details.expected_checkin' => 1,
                        'stay_details.expected_checkout' => 1,
                    ]
                ]
            )->toArray();
    
            // Function to convert UTCDateTime to string
            $getDate = function ($field) {
                if ($field instanceof \MongoDB\BSON\UTCDateTime) {
                    return $field->toDateTime()->format('Y-m-d H:i:s');
                } elseif (is_array($field) && isset($field['$date']['$numberLong'])) {
                    return (new \DateTime('@'.($field['$date']['$numberLong'] / 1000)))->format('Y-m-d H:i:s');
                }
                \Illuminate\Support\Facades\Log::warning('Unexpected date format', ['field' => $field]);
                return null;
            };
    
            // Map transactions to desired format
            return array_map(function ($transaction) use ($getDate) {
                return [
                    'transaction_id' => isset($transaction['_id']) ? (string) $transaction['_id'] : null,
                    'room_id' => isset($transaction['room_id']) ? (string) $transaction['room_id'] : null,
                    'expected_checkin' => isset($transaction['stay_details']['expected_checkin'])
                        ? $getDate($transaction['stay_details']['expected_checkin'])
                        : null,
                    'expected_checkout' => isset($transaction['stay_details']['expected_checkout'])
                        ? $getDate($transaction['stay_details']['expected_checkout'])
                        : null,
                ];
            }, $transactions);
        } catch (\MongoDB\Driver\Exception\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to fetch transaction schedules', [
                'error' => $e->getMessage(),
            ]);
            return [];
        }
    }
    public static function getTransactPerGuest($guestId)
    {
        try {
            // Convert guestId to string if it's an ObjectId
            $guestId = $guestId instanceof ObjectId ? (string) $guestId : (string) $guestId;

            // Validate guestId
            if (!$guestId || !is_string($guestId) || !preg_match('/^[0-9a-f]{24}$/i', $guestId)) {
                Log::warning('Invalid guest_id provided', ['guest_id' => $guestId]);
                return [];
            }

            // Get MongoDB client
            $client = DB::connection('mongodb')->getMongoClient();
            $database = $client->bembang_hotel;
            $collection = $database->transaction;

            // Fetch transactions
            $transactions = $collection->find(['guest_id' => new ObjectId($guestId)])->toArray();

            // Convert transactions to a structured format
            return array_map(function ($transaction) {
                $getDate = function ($field) {
                    if ($field instanceof UTCDateTime) {
                        // Convert UTCDateTime to formatted string
                        return $field->toDateTime()->format('Y-m-d H:i:s');
                    } elseif (is_array($field) && isset($field['$date']['$numberLong'])) {
                        // Handle extended JSON format
                        return (new \DateTime('@'.($field['$date']['$numberLong'] / 1000)))->format('Y-m-d H:i:s');
                    }
                    Log::warning('Unexpected date format', ['field' => $field]);
                    return null;
                };

                return [
                    'transaction_id' => isset($transaction['_id']) ? (string) $transaction['_id'] : null,
                    'transaction_type' => $transaction['transaction_type'] ?? null,
                    'payment' => [
                        'method' => $transaction['payment']['method'] ?? null,
                        'amount' => $transaction['payment']['amount'] ?? null,
                        'currency' => $transaction['payment']['currency'] ?? null,
                        'status' => $transaction['payment']['status'] ?? null,
                        'processed_at' => isset($transaction['payment']['processed_at']) ? $getDate($transaction['payment']['processed_at']) : null,
                    ],
                    'stay_details' => [
                        'expected_checkin' => isset($transaction['stay_details']['expected_checkin']) ? $getDate($transaction['stay_details']['expected_checkin']) : null,
                        'expected_checkout' => isset($transaction['stay_details']['expected_checkout']) ? $getDate($transaction['stay_details']['expected_checkout']) : null,
                        'actual_checkin' => isset($transaction['stay_details']['actual_checkin']) ? $getDate($transaction['stay_details']['actual_checkin']) : null,
                        'actual_checkout' => isset($transaction['stay_details']['actual_checkout']) ? $getDate($transaction['stay_details']['actual_checkout']) : null,
                        'guest_num' => $transaction['stay_details']['guest_num'] ?? null,
                        'stay_hours' => $transaction['stay_details']['stay_hours'] ?? null,
                        'time_allowance' => $transaction['stay_details']['time_allowance'] ?? null,
                    ],
                    'current_status' => $transaction['current_status'] ?? null,
                    'created_at' => isset($transaction['created_at']) ? $getDate($transaction['created_at']) : null,
                    'updated_at' => isset($transaction['updated_at']) ? $getDate($transaction['updated_at']) : null,
                ];
            }, $transactions);
        } catch (\MongoDB\Driver\Exception\InvalidArgumentException $e) {
            Log::error('Invalid ObjectId in getTransactPerGuest', [
                'guest_id' => $guestId,
                'error' => $e->getMessage(),
            ]);
            return [];
        } catch (\MongoDB\Driver\Exception\ConnectionException $e) {
            Log::error('MongoDB connection failed in getTransactPerGuest', [
                'guest_id' => $guestId,
                'error' => $e->getMessage(),
            ]);
            return [];
        } catch (\Exception $e) {
            Log::error('Failed to fetch transactions for guest', [
                'guest_id' => $guestId,
                'error' => $e->getMessage(),
            ]);
            return [];
        }
    }
}