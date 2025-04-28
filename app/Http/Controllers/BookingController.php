<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use MongoDB\BSON\ObjectId;

class BookingController extends Controller
{
    /**
     * Handle check-in process
     */
    public function checkin(Request $request)
    {
        $validated = $request->validate([
            'booking_id' => 'required',
            'room_id' => 'required',
            'guest_id' => 'required',
            'status' => 'required|in:confirmed'
        ]);
    
        try {
            $transactions = DB::connection('mongodb')->getCollection('transactions');
            $rooms = DB::connection('mongodb')->getCollection('rooms');
            $guests = DB::connection('mongodb')->getCollection('guests');
            
            $bookingId = new ObjectId($validated['booking_id']);
            $roomId = new ObjectId($validated['room_id']);
            $guestId = new ObjectId($validated['guest_id']);
    
            // Get current datetime in proper MongoDB format
            $currentDateTime = new \MongoDB\BSON\UTCDateTime(now()->getTimestamp() * 1000);
            
            // Calculate expected checkout (current time + stay hours)
            $stayHours = $request['stay_hours']; // Default 24 hours if not provided
            $checkoutDateTime = new \MongoDB\BSON\UTCDateTime(
                (now()->getTimestamp() + ($stayHours * 3600)) * 1000
            );
    
            // Using database transaction for atomic operations
            $session = DB::connection('mongodb')->getMongoClient()->startSession();
            $session->startTransaction();
    
            try {
                // 1. Update transaction with proper date objects
                $updateTransaction = $transactions->updateOne(
                    ['_id' => $bookingId],
                    [
                        '$set' => [
                            'current_status' => $validated['status'],
                            'stay_details.actual_checkin' => $currentDateTime,
                            'stay_details.expected_checkout' => $checkoutDateTime,
                            'updated_at' => $currentDateTime
                        ]
                    ],
                    ['session' => $session]
                );
    
                // 2. Update room status
                $updateRoom = $rooms->updateOne(
                    ['_id' => $roomId],
                    [
                        '$set' => [
                            'status' => 'occupied',
                            'current_booking' => $bookingId,
                            'updated_at' => $currentDateTime
                        ]
                    ],
                    ['session' => $session]
                );
    
                // 3. Update guest check-in count
                $updateGuest = $guests->updateOne(
                    ['_id' => $guestId],
                    [
                        '$inc' => ['checkin_count' => 1],
                        '$set' => ['updated_at' => $currentDateTime]
                    ],
                    ['session' => $session]
                );
    
                if ($updateTransaction->getModifiedCount() === 0 || 
                    $updateRoom->getModifiedCount() === 0 || 
                    $updateGuest->getModifiedCount() === 0) {
                    $session->abortTransaction();
                    throw new \Exception('Failed to update one or more documents');
                }
    
                $session->commitTransaction();
    
                // Format dates for response
                $formattedDates = [
                    'actual_checkin' => $this->formatMongoDate($currentDateTime),
                    'expected_checkout' => $this->formatMongoDate($checkoutDateTime)
                ];
    
                return response()->json([
                    'success' => true,
                    'message' => 'Check-in completed successfully',
                    'data' => array_merge([
                        'booking_id' => $validated['booking_id'],
                        'new_status' => $validated['status'],
                        'room_status' => 'occupied'
                    ], $formattedDates)
                ]);
    
            } catch (\Exception $e) {
                if ($session->getTransactionState() === \MongoDB\Driver\Session::TRANSACTION_IN_PROGRESS) {
                    $session->abortTransaction();
                }
                throw $e;
            }
    
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Check-in failed: ' . $e->getMessage(),
                'error' => [
                    'code' => $e->getCode()
                ]
            ], 500);
        }
    }
    
    // Helper function to format MongoDB date for response
    protected function formatMongoDate(\MongoDB\BSON\UTCDateTime $date)
    {
        return $date->toDateTime()->format('Y-m-d\TH:i:s.v\Z');
    }
}