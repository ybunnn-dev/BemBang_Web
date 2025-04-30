<?php

namespace App\Http\Controllers;
use MongoDB\BSON\UTCDateTime;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use MongoDB\Driver\Manager;
use MongoDB\Driver\Query;
use App\Models\Guest;
use App\Models\Membership;
use App\Models\Rooms;
use MongoDB\BSON\ObjectId;
use Illuminate\Support\Facades\DB;
use MongoDB\Laravel\Eloquent\Casts\ObjectId as CastsObjectId;

class TransactionController extends Controller
{
    
    public function store(Request $request)
    {
         // Log the incoming request for debugging
        Log::debug('TransactionController::store - Received request', ['request' => $request->all()]);
    
        // Get all input data
        $input = $request->all();
        
        // Check if request is empty or missing required data
        if (empty($input)) {
            Log::error('TransactionController::store - Empty request data');
            return response()->json([
                'success' => false,
                'message' => 'No transaction data provided',
            ], 400);
        }
        
        // Handle guest data properly
        if (isset($input['is_guest']) && $input['is_guest'] === false) {
            if (isset($input['new_guest']) && !empty($input['new_guest'])) {
                // Create a new guest and get the ID
                $input['guest_id'] = Guest::createGuest($input['new_guest']);
                Log::debug('TransactionController::store - Created new guest', ['guest_id' => $input['guest_id']]);
            } else {
                Log::error('TransactionController::store - Missing new_guest data');
                return response()->json([
                    'success' => false,
                    'message' => 'New guest information is required when is_guest is false',
                ], 400);
            }
        }
        
        // Normalize guest_id if it's a MongoDB ObjectID
        if (isset($input['guest_id']['$oid'])) {
            $input['guest_id'] = $input['guest_id']['$oid'];
            Log::debug('TransactionController::store - Normalized guest_id', ['guest_id' => $input['guest_id']]);
        }

        // Validate the incoming request data
        $validator = Validator::make($input, [
            'guest_id' => 'required|string|regex:/^[0-9a-f]{24}$/i',
            'employee_id' => 'required|string|regex:/^[0-9a-f]{24}$/i',
            'room_id' => 'required|string|regex:/^[0-9a-f]{24}$/i',
            'transaction_type' => 'required|string',
            'payments' => 'required|array',
            'payments.*.method' => 'required|string',
            'payments.*.amount' => 'required|numeric|min:0',
            'payments.*.currency' => 'required|string',
            'stay_details.expected_checkin' => 'required|date',
            'stay_details.expected_checkout' => 'required|date|after_or_equal:stay_details.expected_checkin',
            'stay_details.guest_num' => 'required|integer|min:1',
            'stay_details.stay_hours' => 'required|integer|min:1',
            'voucher_id' => 'nullable|string|regex:/^[0-9a-f]{24}$/i',
        ]);


        // Check for validation errors
        if ($validator->fails()) {
            Log::warning('TransactionController::store - Validation failed', [
                'errors' => $validator->errors()->toArray(),
                'input' => $input
            ]);
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
                'debug' => ['input' => $input]
            ], 422);
        }

        try {
            // Test MongoDB connection
            $manager = new Manager(env('DB_URI'));
            $command = new \MongoDB\Driver\Command(['ping' => 1]);
            $manager->executeCommand('bembang_hotel', $command);

            Log::debug('TransactionController::store - MongoDB connection verified');

            // Validate guest existence
            $query = new Query(['_id' => new \MongoDB\BSON\ObjectId($input['guest_id'])]);
            $cursor = $manager->executeQuery('bembang_hotel.guests', $query);
            $thisGuest = $cursor->toArray()[0] ?? null;
            if (!$thisGuest) {
                Log::error('TransactionController::store - Guest not found', ['guest_id' => $input['guest_id']]);
                return response()->json([
                    'success' => false,
                    'message' => 'Guest not found'
                ], 404);
            }

            // Fetch membership details and log
            $membership = null;
            if (isset($thisGuest->membership_id)) {
                $membership = Membership::getSpecificMembership($thisGuest->membership_id);
                Log::debug('TransactionController::store - Retrieved membership', [
                    'membership_id' => $thisGuest->membership_id,
                    'membership' => $membership ? json_encode($membership, JSON_PRETTY_PRINT) : null
                ]);
            } else {
                Log::debug('TransactionController::store - No membership_id for guest', [
                    'guest_id' => $input['guest_id']
                ]);
            }

            $membership_points = 0;
            if ($membership && !empty($membership)) {
                // Access the first membership object
                $membership_data = $membership[0]; // Get the first item in the array
            
                // Use current_status from $input (not $request['current-status'])
                switch ($input['current_status']) {
                    case 'confirmed':
                        $membership_points = $membership_data->check_in_points ?? 0;
                        break;
                    case 'booked':
                        $membership_points = $membership_data->booking_points ?? 0;
                        break;
                    case 'reserved':
                        $membership_points = $membership_data->reservation_points ?? 0;
                        break;
                    default:
                        Log::warning('TransactionController::store - Unknown current_status', [
                            'current_status' => $input['current_status']
                        ]);
                }
            }

            Log::info('TransactionController::store - Retrieved membership points', [
                'guest_id' => $input['guest_id'],
                'points' => $membership_points
            ]);

            function parseToUTCDateTime($dateInput) {
                // If null, return null
                if ($dateInput === null) {
                    return null;
                }
                
                // If it's already a timestamp in milliseconds
                if (is_numeric($dateInput) && strlen($dateInput) >= 12) {
                    return new UTCDateTime((int)$dateInput);
                }
                
                // Try to parse as ISO format
                try {
                    if (is_string($dateInput) && preg_match('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}/', $dateInput)) {
                        return new UTCDateTime((int)(Carbon::createFromFormat('Y-m-d\TH:i:s.u\Z', $dateInput)->getPreciseTimestamp(3)));
                    }
                } catch (\Exception $e) {
                    Log::warning('Failed to parse date as ISO format: ' . $dateInput, ['error' => $e->getMessage()]);
                }
                
                // Try standard Carbon parsing as fallback
                try {
                    return new UTCDateTime((int)(Carbon::parse($dateInput)->getPreciseTimestamp(3)));
                } catch (\Exception $e) {
                    Log::error('Failed to parse date: ' . $dateInput, ['error' => $e->getMessage()]);
                    throw new \Exception('Invalid date format: ' . $dateInput);
                }
            }
           // Prepare stay details with robust date parsing
            $transactionDataStay = [
                'stay_details' => [
                    'actual_checkin' => isset($input['stay_details']['actual_checkin']) 
                        ? parseToUTCDateTime($input['stay_details']['actual_checkin'])
                        : null,
                    'actual_checkout' => isset($input['stay_details']['actual_checkout']) 
                        ? parseToUTCDateTime($input['stay_details']['actual_checkout'])
                        : null,
                    'expected_checkin' => parseToUTCDateTime($input['stay_details']['expected_checkin']),
                    'expected_checkout' => parseToUTCDateTime($input['stay_details']['expected_checkout']),
                    'guest_num' => (int)$input['stay_details']['guest_num'],
                    'stay_hours' => (int)$input['stay_details']['stay_hours'],
                    'time_allowance' => (int)$input['stay_details']['time_allowance']
                ]
            ];

            $guestID = new ObjectId($input['guest_id']);
            $roomID = new ObjectId($input['room_id']);
            $transactionData = [
                'guest_id' => $guestID,
                'employee_id' => $input['employee_id'],
                'room_id' => $roomID,
                'voucher_id' => $input['voucher_id'] ?? null,
                'transaction_type' => $input['transaction_type'],
                'payments' => $input['payments'],
                'stay_details' => $transactionDataStay['stay_details'],
                'current_status' => $input['current_status'] ?? 'checked-in',
                'audit_log' => $input['audit_log'] ?? [[
                    'action' => 'checked-in',
                    'by' => $input['employee_id'],
                    'timestamp' => new UTCDateTime((int)Carbon::now()->getPreciseTimestamp(3)),
                    'points_earned' => $membership_points
                ]],
                'created_at' => new UTCDateTime((int)Carbon::now()->getPreciseTimestamp(3)),
                'updated_at' => new UTCDateTime((int)Carbon::now()->getPreciseTimestamp(3)),
            ];
            // Log transaction data for debugging
            Log::debug('TransactionController::store - Prepared transaction data', ['data' => $transactionData]);

            // Add payment method specific details for GCash
            if (isset($transactionData['payments'][0]['method']) && 
                strtolower($transactionData['payments'][0]['method']) === 'gcash') {
                $transactionData['payments'][0]['details'] = [
                    'reference_no' => $input['payments'][0]['reference_no'] ?? 'N/A',
                    'account_name' => $input['payments'][0]['account_name'] ?? null
                ];
                Log::debug('TransactionController::store - Added GCash payment details', [
                    'details' => $transactionData['payments'][0]['details']
                ]);
            }

            // Create the transaction
            $transaction = Transaction::create($transactionData);
            if (!$transaction || !$transaction->_id) {
                throw new \Exception('Failed to create transaction or retrieve _id');
            }
            Log::info('TransactionController::store - Transaction created', [
                'transaction_id' => $transaction->_id,
                'attributes' => $transaction->attributes
            ]);


            // Update room status to occupied
            if($request['current-status'] == 'confirmed'){
                $bulk = new \MongoDB\Driver\BulkWrite();
                $bulk->update(
                    ['_id' => new \MongoDB\BSON\ObjectId($input['room_id'])],
                    ['$set' => ['status' => 'occupied']],
                    ['multi' => false]
                );
                $result = $manager->executeBulkWrite('bembang_hotel.rooms', $bulk);
                if ($result->getModifiedCount() === 0) {
                    Log::warning('TransactionController::store - Failed to update room status', ['room_id' => $input['room_id']]);
                }
            }

            // Update guest points
            $bulk = new \MongoDB\Driver\BulkWrite();
            $bulk->update(
                ['_id' => new \MongoDB\BSON\ObjectId($input['guest_id'])],
                ['$set' => ['points' => $membership_points]],
                ['multi' => false]
            );
            $result = $manager->executeBulkWrite('bembang_hotel.guests', $bulk);
            if ($result->getModifiedCount() === 0) {
                Log::warning('TransactionController::store - Failed to update guest points', ['guest_id' => $input['guest_id']]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Transaction created successfully',
                'data' => $transaction->attributes,
                'debug' => ['transaction_id' => $transaction->_id]
            ], 201);

        } catch (\MongoDB\Driver\Exception\ConnectionException $e) {
            Log::error('TransactionController::store - MongoDB connection error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to connect to MongoDB',
                'debug' => ['error' => $e->getMessage()]
            ], 500);
        } catch (\MongoDB\Driver\Exception\Exception $e) {
            Log::error('TransactionController::store - MongoDB error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'MongoDB error occurred while creating transaction',
                'debug' => ['error' => $e->getMessage()]
            ], 500);
        } catch (\Exception $e) {
            Log::error('TransactionController::store - General error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to create transaction',
                'debug' => ['error' => $e->getMessage()]
            ], 500);
        }
    }

    public function getBooking()
    {
        try {
            // Get transactions and safely convert to collection
            $transactions = collect(Transaction::getBookingTransaction() ?? []);
            
            // Return empty collection structure if no transactions
            if ($transactions->isEmpty()) {
                return view('frontdesk.bookings', [
                    'bookings' => collect([]) // Return empty collection
                ]);
            }
    
            $enhancedTransactions = $transactions->map(function ($transaction) {
                // Safely handle null transaction
                if (empty($transaction)) {
                    return null;
                }
    
                // Ensure we're working with an object
                $transaction = is_array($transaction) ? (object)$transaction : $transaction;
                
                // Initialize default values
                $guest = ['firstName' => 'N/A', 'lastName' => 'N/A'];
                $room = ['number' => 'N/A', 'type' => 'N/A'];
                $checkinDateTime = ['date' => 'N/A', 'time' => 'N/A'];
                $checkoutDateTime = ['date' => 'N/A', 'time' => 'N/A'];
                $amount = 0;
    
                try {
                    // Get related data with null checks
                    if (!empty($transaction->guest_id)) {
                        $guestId = (string)$transaction->guest_id;
                        $guestData = Guest::getSpecificGuest($guestId);
                        if ($guestData) {
                            $guest = [
                                'firstName' => $guestData['firstName'] ?? 'N/A',
                                'lastName' => $guestData['lastName'] ?? 'N/A'
                            ];
                        }
                    }
    
                    if (!empty($transaction->room_id)) {
                        $roomData = Rooms::getSpecificRoom($transaction->room_id);
                        if ($roomData) {
                            $room = [
                                'number' => $roomData['room_no'] ?? 'N/A',
                                'type' => $roomData['room_type_details']['type_name'] ?? 'N/A'
                            ];
                        }
                    }
    
                    // Process dates
                    $stayDetails = $transaction->stay_details ?? (object)[];
                    $checkinDateTime = $this->parseMongoDateToArray(
                        is_object($stayDetails) ? ($stayDetails->expected_checkin ?? null) : ($stayDetails['expected_checkin'] ?? null)
                    ) ?? ['date' => 'N/A', 'time' => 'N/A'];
                    
                    $checkoutDateTime = $this->parseMongoDateToArray(
                        is_object($stayDetails) ? ($stayDetails->expected_checkout ?? null) : ($stayDetails['expected_checkout'] ?? null)
                    ) ?? ['date' => 'N/A', 'time' => 'N/A'];
                    
                    // Process payment amount
                    $meta = $transaction->meta ?? (object)[];
                    $amount = is_object($meta) ? ($meta->original_rate ?? 0) : ($meta['original_rate'] ?? 0);
    
                } catch (\Exception $e) {
                    Log::error("Error processing transaction data: " . $e->getMessage());
                }
    
                return [
                    'id' => $this->extractId($transaction->_id ?? null) ?? 'N/A',
                    'short_id' => substr($this->extractId($transaction->_id ?? null) ?? '', -8),
                    'guest' => $guest,
                    'room' => $room,
                    'checkin' => $checkinDateTime,
                    'checkout' => $checkoutDateTime,
                    'status' => $transaction->current_status ?? 'N/A',
                    'amount' => number_format($amount, 2),
                    'raw_data' => $transaction
                ];
            })->filter(); // Remove any null entries from the collection
    
            return view('frontdesk.bookings', [
                'bookings' => $enhancedTransactions
            ]);
    
        } catch (\Exception $e) {
            Log::error("Error in getBooking: " . $e->getMessage());
            return view('frontdesk.bookings', [
                'bookings' => collect([]) // Return empty collection on error
            ]);
        }
    }
    public function checkout(Request $request)
    {
        $client = DB::connection('mongodb')->getMongoClient();
        $database = $client->bembang_hotel;
        $transactions = $database->transactions;
        $rooms = $database->rooms;
    
        $validated = $request->validate([
            'transaction_id' => 'required',
            'room_id' => 'required',
        ]);
    
        try {
            // Start MongoDB transaction
            $session = $client->startSession();
            $session->startTransaction();
    
            // Convert to milliseconds (integer)
            $timestamp = (int) (now()->timestamp * 1000);
    
            // 1. Update the transaction
            $transactionUpdate = $transactions->updateOne(
                ['_id' => new ObjectId($validated['transaction_id'])],
                [
                    '$set' => [
                        'current_status' => 'completed',
                        'stay_details.actual_checkout' => new \MongoDB\BSON\UTCDateTime($timestamp),
                        'updated_at' => new UTCDateTime($timestamp)
                    ],
                    '$push' => [
                        'audit_log' => [
                            'action' => 'checked_out',
                            'by' => auth()->id(),
                            'timestamp' => new UTCDateTime($timestamp),
                            '_id' => new ObjectId()
                        ]
                    ]
                ],
                ['session' => $session]
            );
    
            // 2. Update the room status
            $roomUpdate = $rooms->updateOne(
                ['_id' => new ObjectId($validated['room_id'])],
                [
                    '$set' => [
                        'status' => 'available',
                        'updated_at' => new UTCDateTime($timestamp)
                    ]
                ],
                ['session' => $session]
            );
    
            // Commit transaction
            $session->commitTransaction();
    
            return response()->json([
                'success' => true,
                'message' => 'Checkout completed successfully'
            ]);
    
        } catch (\Exception $e) {
            // Abort transaction on error
            if (isset($session)) {
                $session->abortTransaction();
            }
    
            Log::error('Checkout failed: ' . $e->getMessage());
    
            return response()->json([
                'success' => false,
                'message' => 'Checkout failed: ' . $e->getMessage()
            ], 500);
        }
    }
    public function getReservation()
    {
        // Set the default timezone to Asia/Manila
        date_default_timezone_set('Asia/Manila');
        
        $transactions = collect(Transaction::getReservationTransaction());
        
        $enhancedTransactions = $transactions->map(function ($transaction) {
            $transaction = is_array($transaction) ? (object)$transaction : $transaction;
            $guestId = (string)$transaction->guest_id;
            $guest = Guest::getSpecificGuest($guestId);
            $room = Rooms::getSpecificRoom(($transaction->room_id));
            
            // Process dates with timezone conversion
            $stayDetails = $transaction->stay_details ?? (object)[];
            
            // Convert dates to Philippine timezone
            $checkinDateTime = $this->parseMongoDateToArray(
                is_object($stayDetails) ? ($stayDetails->expected_checkin ?? null) : ($stayDetails['expected_checkin'] ?? null),
                'Asia/Manila' // Pass timezone parameter
            );
            
            $checkoutDateTime = $this->parseMongoDateToArray(
                is_object($stayDetails) ? ($stayDetails->expected_checkout ?? null) : ($stayDetails['expected_checkout'] ?? null),
                'Asia/Manila' // Pass timezone parameter
            );
            
            $meta = $transaction->meta ?? (object)[];
            $amount = is_object($meta) ? ($meta->original_rate ?? 0) : ($meta['original_rate'] ?? 0);
            
            $roomNo = is_array($room) ? ($room['room_no'] ?? 'N/A') : ($room->room_no ?? 'N/A');
            $roomTypeName = is_array($room) 
                ? ($room['room_type_details']['type_name'] ?? 'N/A') 
                : ($room->room_type_details->type_name ?? 'N/A');
    
            return [
                'id' => $this->extractId($transaction->_id),
                'short_id' => substr($this->extractId($transaction->_id), -8),
                'guest' => $guest,
                'room' => [
                    'number' => $roomNo,
                    'type' => $roomTypeName
                ],
                'checkin' => [
                    'date' => $checkinDateTime['date'],
                    'time' => $checkinDateTime['time']
                ],
                'checkout' => [
                    'date' => $checkoutDateTime['date'],
                    'time' => $checkoutDateTime['time']
                ],
                'status' => $transaction->current_status,
                'amount' => number_format($amount, 2),
                'raw_data' => $transaction
            ];
        });
        
        return view('frontdesk.reservations', [
            'reservation' => $enhancedTransactions
        ]);
    }
    protected function extractId($id)
    {
        // If it's an ObjectId object
        if (is_object($id) && method_exists($id, '__toString')) {
            return (string)$id;
        }
        // If it's a standard object, we need to handle it differently
        if (is_object($id) && !method_exists($id, '__toString')) {
            // Convert to array, then to JSON if it's a stdClass
            return json_encode($id);
        }
        // If it's an array with $oid key (MongoDB format)
        if (is_array($id) && isset($id['$oid'])) {
            return $id['$oid'];
        }
        // If it's already a string or can be cast safely
        return (string)$id;
    }
    
    protected function parseMongoDateToArray($dateField)
    {
        if (!$dateField) {
            return [
                'date' => 'N/A',
                'time' => '12:00 PM' // Set default time with AM/PM
            ];
        }
        
        try {
            $timezone = new \DateTimeZone('Asia/Manila');
            
            if (is_array($dateField)) {
                $milliseconds = $dateField['$date']['$numberLong'] ?? null;
            } elseif (is_object($dateField) && isset($dateField->{'$date'})) {
                $milliseconds = $dateField->{'$date'}->{'$numberLong'} ?? null;
            } elseif (is_object($dateField) && $dateField instanceof UTCDateTime) {
                $dateTime = $dateField->toDateTime()->setTimezone($timezone);
                return [
                    'date' => $dateTime->format('F j, Y'),  // "May 15, 2023"
                    'time' => $dateTime->format('h:i A')     // "02:30 PM"
                ];
            } else {
                $milliseconds = null;
            }

            if ($milliseconds !== null) {
                $carbon = \Carbon\Carbon::createFromTimestampMs($milliseconds)
                            ->setTimezone($timezone);
                return [
                    'date' => $carbon->format('F j, Y'),  // Full month name
                    'time' => $carbon->format('h:i A')    // 12-hour with AM/PM
                ];
            }

            return [
                'date' => 'N/A',
                'time' => '12:00 PM'
            ];
            
        } catch (\Exception $e) {
            return [
                'date' => 'N/A',
                'time' => '12:00 PM'
            ];
        }
    }
    public function processPayment(Request $request)
    {
        try {
            Log::info('Received payment data:', $request->all());

            // Validate required fields
            $validated = $request->validate([
                'transaction_id' => 'required|string',
                'guest_id' => 'required|string',
                'payment' => 'required|array',
                'payment.method' => 'required|string|in:gcash,cash',
                'payment.amount' => 'required|numeric',
                'payment.currency' => 'required|string',
                'payment.status' => 'required|string',
                'payment.processed_at' => 'required|string',
                'update_status' => 'required|string'
            ]);

            // Get MongoDB client and collections
            $client = DB::connection('mongodb')->getMongoClient();
            $database = $client->selectDatabase('bembang_hotel');
            $transactionsCollection = $database->selectCollection('transactions');
            $guestsCollection = $database->selectCollection('guests');
            $membershipsCollection = $database->selectCollection('membership');

            // Extract data
            $transactionId = $request->input('transaction_id');
            $guestId = $request->input('guest_id');
            $payment = $request->input('payment');
            $updateStatus = $request->input('update_status');

            // Start transaction
            $session = $client->startSession();
            $session->startTransaction();

            try {
                // 1. Update the transaction - push new payment and update status
                $transactionUpdate = $transactionsCollection->updateOne(
                    ['_id' => new ObjectId($transactionId)],
                    [
                        '$push' => ['payments' => $payment],
                        '$set' => [
                            'current_status' => $updateStatus,
                            'status' => $updateStatus,
                            'updated_at' => new \MongoDB\BSON\UTCDateTime()
                        ],
                        '$addToSet' => [
                            'audit_log' => [
                                'action' => 'payment_processed',
                                'by' => new ObjectId($guestId),
                                'timestamp' => new UTCDateTime(),
                                'payment_method' => $payment['method'],
                                'amount' => $payment['amount']
                            ]
                        ]
                    ],
                    ['session' => $session]
                );

                if ($transactionUpdate->getModifiedCount() === 0) {
                    throw new \Exception("Failed to update transaction");
                }

                // 2. Get the guest's membership to determine points
                $guest = $guestsCollection->findOne(
                    ['_id' => new ObjectId($guestId)],
                    ['session' => $session]
                );

                if (!$guest) {
                    throw new \Exception("Guest not found");
                }

                $membershipId = $guest['membership_id'] ?? null;
                
                if (!$membershipId) {
                    throw new \Exception("Guest has no membership");
                }

                // 3. Get membership details to determine check-in points
                $membership = $membershipsCollection->findOne(
                    ['_id' => $membershipId],
                    ['session' => $session]
                );

                if (!$membership) {
                    throw new \Exception("Membership not found");
                }

                $checkInPoints = $membership['check_in_points'] ?? 0;

                // 4. Update guest's points and check-in count
                $guestUpdate = $guestsCollection->updateOne(
                    ['_id' => new ObjectId($guestId)],
                    [
                        '$inc' => [
                            'points' => (int)$checkInPoints,
                            'checkin_count' => 1
                        ]
                    ],
                    ['session' => $session]
                );

                if ($guestUpdate->getModifiedCount() === 0) {
                    throw new \Exception("Failed to update guest points");
                }

                // Commit transaction
                $session->commitTransaction();

                return response()->json([
                    'success' => true,
                    'message' => 'Payment processed successfully',
                    'data' => [
                        'transaction_id' => $transactionId,
                        'payment_method' => $payment['method'],
                        'amount' => $payment['amount'],
                        'new_status' => $updateStatus,
                        'points_added' => $checkInPoints,
                        'processed_at' => now()->toDateTimeString()
                    ]
                ]);

            } catch (\Exception $e) {
                $session->abortTransaction();
                throw $e;
            } finally {
                $session->endSession();
            }

        } catch (\Exception $e) {
            Log::error("Payment processing failed: " . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Payment processing failed: ' . $e->getMessage()
            ], 500);
        }
    }

    
}