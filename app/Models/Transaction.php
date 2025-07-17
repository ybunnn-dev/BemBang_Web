<?php

namespace App\Models;

use Illuminate\Support\Facades\DB; 
use MongoDB\Driver\Manager;
use MongoDB\Driver\BulkWrite;
use MongoDB\Driver\Query;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class Transaction
{
    protected $connection = 'mongodb';
    protected $collection = 'transactions';
    protected $manager;
    protected $database = 'bembang_hotel';

    protected $attributes = [];

    protected $fillable = [
        'guest_id',
        'employee_id',
        'room_id',
        'voucher_id',
        'transaction_type',
        'payments',
        'stay_details',
        'current_status',
        'cancellation',
        'audit_log',
        'meta',
    ];

    public function __construct()
    {
        $this->manager = new Manager(env('DB_URI'));
    }

    public static function create(array $attributes)
    {
        try {
            $instance = new self();
            $bulk = new BulkWrite();
            $document = array_intersect_key($attributes, array_flip($instance->fillable));

            // Convert dates to UTCDateTime
            if (isset($document['stay_details']['expected_checkin'])) {
                $document['stay_details']['expected_checkin'] = new UTCDateTime(Carbon::parse($document['stay_details']['expected_checkin'])->getTimestampMs());
            }
            if (isset($document['stay_details']['expected_checkout'])) {
                $document['stay_details']['expected_checkout'] = new UTCDateTime(Carbon::parse($document['stay_details']['expected_checkout'])->getTimestampMs());
            }
            if (isset($document['payments'][0]['processed_at'])) {
                $document['payments'][0]['processed_at'] = new UTCDateTime(Carbon::parse($document['payments'][0]['processed_at'])->getTimestampMs());
            }
            if (isset($document['audit_log'][0]['timestamp'])) {
                $document['audit_log'][0]['timestamp'] = new UTCDateTime(Carbon::parse($document['audit_log'][0]['timestamp'])->getTimestampMs());
            }
            if (isset($document['created_at'])) {
                $document['created_at'] = new UTCDateTime(Carbon::parse($document['created_at'])->getTimestampMs());
            }
            if (isset($document['updated_at'])) {
                $document['updated_at'] = new UTCDateTime(Carbon::parse($document['updated_at'])->getTimestampMs());
            }

            // Ensure no _id is set
            unset($document['_id']);

            // Insert document
            $id = $bulk->insert($document);
            $result = $instance->manager->executeBulkWrite("{$instance->database}.{$instance->collection}", $bulk);

            // Retrieve the inserted document
            $query = new Query(['_id' => $id]);
            $cursor = $instance->manager->executeQuery("{$instance->database}.{$instance->collection}", $query);
            $document = $cursor->toArray()[0] ?? null;

            if ($document) {
                $transaction = new self();
                $transaction->attributes = (array) $document;
                $transaction->attributes['_id'] = (string) $document->_id;
                return $transaction;
            }

            Log::error('Failed to retrieve inserted transaction', ['id' => (string) $id]);
            return null;
        } catch (\MongoDB\Driver\Exception\Exception $e) {
            Log::error('Failed to create transaction', [
                'error' => $e->getMessage(),
                'attributes' => $attributes
            ]);
            throw $e;
        }
    }

    public function __get($key)
    {
        return $this->attributes[$key] ?? null;
    }

    public function __set($key, $value)
    {
        $this->attributes[$key] = $value;
    }

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
    public static function getBookingTransaction()
    {
        try {
            $instance = new self();
            $filter = [
                'transaction_type' => 'Booking',
                'current_status' => [
                    '$in' => ['pending', 'booked', 'no show', 'cancelled', 'refunded']
                ]
            ];
            
            $query = new Query($filter);
            $cursor = $instance->manager->executeQuery("{$instance->database}.{$instance->collection}", $query);
            $bookings = $cursor->toArray();
            
            if (count($bookings) > 0) {
                return $bookings;
            } else {
                return ['error' => 'No booking transactions found'];
            }
        } catch (\MongoDB\Driver\Exception\Exception $e) {
            return ['error' => 'Database error: ' . $e->getMessage()];
        }
    }
    
    public static function getReservationTransaction()
    {
        try {
            $instance = new self();
            $filter = [
                'transaction_type' => 'Reservation',
                'current_status' => [
                    '$in' => ['pending', 'reserved', 'no show', 'cancelled']
                ]
            ];
            
            $query = new Query($filter);
            $cursor = $instance->manager->executeQuery("{$instance->database}.{$instance->collection}", $query);
            $bookings = $cursor->toArray();
            
            if (count($bookings) > 0) {
                return $bookings;
            } else {
                return ['error' => 'No booking transactions found'];
            }
        } catch (\MongoDB\Driver\Exception\Exception $e) {
            return ['error' => 'Database error: ' . $e->getMessage()];
        }
    }

    public static function getTransactionSchedules()
    {
        try {
            $instance = new self();
            $query = new Query([], [
                'projection' => [
                    '_id' => 1,
                    'room_id' => 1,
                    'stay_details.expected_checkin' => 1,
                    'stay_details.expected_checkout' => 1,
                ]
            ]);
            $cursor = $instance->manager->executeQuery("{$instance->database}.{$instance->collection}", $query);
            $transactions = $cursor->toArray();

            return array_map(function ($transaction) {
                return [
                    'transaction_id' => (string) $transaction->_id,
                    'room_id' => (string) $transaction->room_id,
                    'expected_checkin' => isset($transaction->stay_details->expected_checkin)
                        ? $transaction->stay_details->expected_checkin->toDateTime()->format('Y-m-d H:i:s')
                        : null,
                    'expected_checkout' => isset($transaction->stay_details->expected_checkout)
                        ? $transaction->stay_details->expected_checkout->toDateTime()->format('Y-m-d H:i:s')
                        : null,
                ];
            }, $transactions);
        } catch (\MongoDB\Driver\Exception\Exception $e) {
            Log::error('Failed to fetch transaction schedules', [
                'error' => $e->getMessage(),
            ]);
            return [];
        }
    }

    
    public static function getTransactPerGuest($guestId)
    {
        try {
            $guestId = $guestId instanceof ObjectId ? (string) $guestId : (string) $guestId;

            if (!$guestId || !is_string($guestId) || !preg_match('/^[0-9a-f]{24}$/i', $guestId)) {
                Log::warning('Invalid guest_id provided', ['guest_id' => $guestId]);
                return [];
            }

            $instance = new self();
            $query = new Query(['guest_id' => $guestId]);
            $cursor = $instance->manager->executeQuery("{$instance->database}.{$instance->collection}", $query);
            $transactions = $cursor->toArray();

            return array_map(function ($transaction) {
                $getDate = function ($field) {
                    if ($field instanceof UTCDateTime) {
                        return $field->toDateTime()->format('Y-m-d H:i:s');
                    }
                    return $field;
                };

                return [
                    'transaction_id' => (string) $transaction->_id,
                    'transaction_type' => $transaction->transaction_type ?? null,
                    'payments' => array_map(function ($payment) use ($getDate) {
                        return [
                            'method' => $payment->method ?? null,
                            'amount' => $payment->amount ?? null,
                            'currency' => $payment->currency ?? null,
                            'status' => $payment->status ?? null,
                            'processed_at' => isset($payment->processed_at) ? $getDate($payment->processed_at) : null,
                        ];
                    }, (array) ($transaction->payments ?? [])),
                    'stay_details' => [
                        'expected_checkin' => isset($transaction->stay_details->expected_checkin)
                            ? $getDate($transaction->stay_details->expected_checkin)
                            : null,
                        'expected_checkout' => isset($transaction->stay_details->expected_checkout)
                            ? $getDate($transaction->stay_details->expected_checkout)
                            : null,
                        'actual_checkin' => isset($transaction->stay_details->actual_checkin)
                            ? $getDate($transaction->stay_details->actual_checkin)
                            : null,
                        'actual_checkout' => isset($transaction->stay_details->actual_checkout)
                            ? $getDate($transaction->stay_details->actual_checkout)
                            : null,
                        'guest_num' => $transaction->stay_details->guest_num ?? null,
                        'stay_hours' => $transaction->stay_details->stay_hours ?? null,
                        'time_allowance' => $transaction->stay_details->time_allowance ?? null,
                    ],
                    'current_status' => $transaction->current_status ?? null,
                    'created_at' => isset($transaction->created_at)
                        ? $getDate($transaction->created_at)
                        : null,
                    'updated_at' => isset($transaction->updated_at)
                        ? $getDate($transaction->updated_at)
                        : null,
                ];
            }, $transactions);
        } catch (\MongoDB\Driver\Exception\Exception $e) {
            Log::error('Failed to fetch transactions for guest', [
                'guest_id' => $guestId,
                'error' => $e->getMessage(),
            ]);
            return [];
        }
    }

    public static function getSpecificTransaction($roomId)
    {
        try {
            $instance = new self();
            $timezone = 'Asia/Manila'; // Explicitly set to Philippine time
            
            // Convert roomId to ObjectId if it's a string
            $roomId = is_string($roomId) ? new ObjectId($roomId) : $roomId;
            
            // Query for active transactions
            $filter = [
                'room_id' => $roomId,
                'current_status' => ['$in' => ['pending', 'confirmed']]
            ];
            
            $options = [
                'sort' => ['created_at' => -1],
                'limit' => 1
            ];
            
            $query = new Query($filter, $options);
            $cursor = $instance->manager->executeQuery("{$instance->database}.{$instance->collection}", $query);
            $transactions = $cursor->toArray();
            
            if (count($transactions) > 0) {
                $transaction = $transactions[0];
                $guest = Guest::getSpecificGuest($transaction->guest_id);
                
                // Improved MongoDB date converter with explicit timezone handling
                $convertDate = function ($date) use ($timezone) {
                    if (!$date) return null;
                    
                    try {
                        // Handle different MongoDB date formats
                        if ($date instanceof \MongoDB\BSON\UTCDateTime) {
                            // Convert MongoDB UTCDateTime to Carbon
                            return \Carbon\Carbon::createFromTimestamp($date->toDateTime()->getTimestamp())
                                ->setTimezone($timezone)
                                ->format('Y-m-d H:i:s');
                        } elseif (is_object($date) && isset($date->{'$date'})) {
                            // Handle $date object format
                            $timestamp = $date->{'$date'};
                            if (is_numeric($timestamp)) {
                                $timestamp = (int)($timestamp / 1000); // Convert from milliseconds if needed
                            }
                            return \Carbon\Carbon::createFromTimestamp($timestamp)
                                ->setTimezone($timezone)
                                ->format('Y-m-d H:i:s');
                        } elseif (is_array($date) && isset($date['$date'])) {
                            // Handle $date array format
                            $timestamp = $date['$date'];
                            if (is_numeric($timestamp)) {
                                $timestamp = (int)($timestamp / 1000); // Convert from milliseconds if needed
                            }
                            return \Carbon\Carbon::createFromTimestamp($timestamp)
                                ->setTimezone($timezone)
                                ->format('Y-m-d H:i:s');
                        } else {
                            // Last resort - try to parse whatever we got
                            return \Carbon\Carbon::parse($date)
                                ->setTimezone($timezone)
                                ->format('Y-m-d H:i:s');
                        }
                    } catch (\Exception $e) {
                        \Log::warning('Date conversion failed', [
                            'date' => json_encode($date),
                            'error' => $e->getMessage()
                        ]);
                        return null;
                    }
                };
                
                // Preserve the original stay_details structure but with converted dates
                $stayDetails = (array)$transaction->stay_details;
                if (isset($stayDetails['expected_checkin'])) {
                    $stayDetails['expected_checkin'] = $convertDate($stayDetails['expected_checkin']);
                }
                if (isset($stayDetails['expected_checkout'])) {
                    $stayDetails['expected_checkout'] = $convertDate($stayDetails['expected_checkout']);
                }
                if (isset($stayDetails['actual_checkin'])) {
                    $stayDetails['actual_checkin'] = $convertDate($stayDetails['actual_checkin']);
                }
                if (isset($stayDetails['actual_checkout'])) {
                    $stayDetails['actual_checkout'] = $convertDate($stayDetails['actual_checkout']);
                }
                
                // Build response with timezone-adjusted dates
                return (object) [
                    'id' => (string) $transaction->_id,
                    'transaction_type' => $transaction->transaction_type ?? null,
                    'current_status' => $transaction->current_status ?? null,
                    'expected_checkin' => $convertDate($transaction->stay_details->expected_checkin ?? null),
                    'expected_checkout' => $convertDate($transaction->stay_details->expected_checkout ?? null),
                    'stay_details' => $stayDetails,
                    'guest' => $guest,
                    'payments' => isset($transaction->payments) ? (array) $transaction->payments : [],
                    'created_at' => $convertDate($transaction->created_at ?? null),
                    'updated_at' => $convertDate($transaction->updated_at ?? null),
                    'meta' => $transaction->meta,
                    // Metadata
                    '_timezone' => $timezone,
                    '_date_format' => 'Y-m-d H:i:s'
                ];
            }
            
            return null;
        } catch (\MongoDB\Driver\Exception\Exception $e) {
            \Log::error('Transaction lookup failed', [
                'room_id' => (string) $roomId,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }
    public static function getAllTransact()
    {
        try {
            $instance = new self();
            $timezone = 'Asia/Manila'; // Explicitly set to Philippine time
            
            // Query for all transactions
            $filter = []; // Empty filter to get all documents
            $options = [
                'sort' => ['created_at' => -1] // Sort by most recent first
            ];
            
            $query = new Query($filter, $options);
            $cursor = $instance->manager->executeQuery("{$instance->database}.{$instance->collection}", $query);
            $transactions = $cursor->toArray();
            
            $convertDate = function ($date) use ($timezone) {
                if (!$date) return null;
                
                try {
                    if ($date instanceof \MongoDB\BSON\UTCDateTime) {
                        return \Carbon\Carbon::createFromTimestamp($date->toDateTime()->getTimestamp())
                            ->setTimezone($timezone)
                            ->format('Y-m-d H:i:s');
                    } elseif (is_object($date) && isset($date->{'$date'})) {
                        $timestamp = $date->{'$date'};
                        if (is_numeric($timestamp)) {
                            $timestamp = (int)($timestamp / 1000);
                        }
                        return \Carbon\Carbon::createFromTimestamp($timestamp)
                            ->setTimezone($timezone)
                            ->format('Y-m-d H:i:s');
                    } elseif (is_array($date) && isset($date['$date'])) {
                        $timestamp = $date['$date'];
                        if (is_numeric($timestamp)) {
                            $timestamp = (int)($timestamp / 1000);
                        }
                        return \Carbon\Carbon::createFromTimestamp($timestamp)
                            ->setTimezone($timezone)
                            ->format('Y-m-d H:i:s');
                    } else {
                        return \Carbon\Carbon::parse($date)
                            ->setTimezone($timezone)
                            ->format('Y-m-d H:i:s');
                    }
                } catch (\Exception $e) {
                    \Log::warning('Date conversion failed', [
                        'date' => json_encode($date),
                        'error' => $e->getMessage()
                    ]);
                    return null;
                }
            };
            
            $result = [];
            foreach ($transactions as $transaction) {
                $guest = Guest::getSpecificGuest($transaction->guest_id);
                
                $stayDetails = (array)$transaction->stay_details;
                if (isset($stayDetails['expected_checkin'])) {
                    $stayDetails['expected_checkin'] = $convertDate($stayDetails['expected_checkin']);
                }
                if (isset($stayDetails['expected_checkout'])) {
                    $stayDetails['expected_checkout'] = $convertDate($stayDetails['expected_checkout']);
                }
                if (isset($stayDetails['actual_checkin'])) {
                    $stayDetails['actual_checkin'] = $convertDate($stayDetails['actual_checkin']);
                }
                if (isset($stayDetails['actual_checkout'])) {
                    $stayDetails['actual_checkout'] = $convertDate($stayDetails['actual_checkout']);
                }
                
                $result[] = (object) [
                    'id' => (string) $transaction->_id,
                    'transaction_type' => $transaction->transaction_type ?? null,
                    'current_status' => $transaction->current_status ?? null,
                    'expected_checkin' => $convertDate($transaction->stay_details->expected_checkin ?? null),
                    'expected_checkout' => $convertDate($transaction->stay_details->expected_checkout ?? null),
                    'stay_details' => $stayDetails,
                    'guest' => $guest,
                    'payments' => isset($transaction->payments) ? (array) $transaction->payments : [],
                    'created_at' => $convertDate($transaction->created_at ?? null),
                    'updated_at' => $convertDate($transaction->updated_at ?? null),
                    'meta' => $transaction->meta ?? null,
                    '_timezone' => $timezone,
                    '_date_format' => 'Y-m-d H:i:s'
                ];
            }
            
            return $result;
        } catch (\MongoDB\Driver\Exception\Exception $e) {
            \Log::error('Failed to fetch all transactions', [
                'error' => $e->getMessage()
            ]);
            return [];
        }
    }
    // Stub relationships
    public function guest()
    {
        return new class { public function find($id) { return null; } };
    }

    public function employee()
    {
        return new class { public function find($id) { return null; } };
    }

    public function room()
    {
        return new class { public function find($id) { return null; } };
    }

    public function voucher()
    {
        return new class { public function find($id) { return null; } };
    }
}
