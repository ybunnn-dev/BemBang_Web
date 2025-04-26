<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use MongoDB\Driver\Manager;
use MongoDB\Driver\Query;
use App\Models\Guest;
use App\Models\Membership;

class TransactionController extends Controller
{
    public function store(Request $request)
    {
        // Log the incoming request for debugging
        Log::debug('TransactionController::store - Received request', ['request' => $request->all()]);
        
        if(!$request['is_guest']){
            $request['guest_id'] = Guest::createGuest($request->new_guest);
        }
        // Normalize guest_id if it's a MongoDB ObjectID
        $input = $request->all();
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
                switch ($input['current_status'] ?? 'checked-in') {
                    case 'checked-in':
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

            // Prepare the transaction data
            $transactionData = [
                'guest_id' => $input['guest_id'],
                'employee_id' => $input['employee_id'],
                'room_id' => $input['room_id'],
                'voucher_id' => $input['voucher_id'] ?? null,
                'transaction_type' => $input['transaction_type'],
                'payments' => $input['payments'],
                'stay_details' => $input['stay_details'],
                'current_status' => $input['current_status'] ?? 'checked-in',
                'audit_log' => $input['audit_log'] ?? [[
                    'action' => 'checked-in',
                    'by' => $input['employee_id'],
                    'timestamp' => Carbon::now()->toDateTimeString(),
                    'points_earned' => $membership_points
                ]],
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()
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
            if(!$request['current-status'] == 'checked-in'){
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
}