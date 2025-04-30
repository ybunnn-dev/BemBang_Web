<?php

namespace App\Http\Controllers;

use App\Models\Rooms;
use App\Models\MongoRoomType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use MongoDB\BSON\UTCDateTime;
use MongoDB\BSON\ObjectId;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use MongoDB\Laravel\Eloquent\DocumentModel;

class ManagementController extends Controller
{
    public function getPerformance(Request $request)
    {
        try {
            $client = DB::connection('mongodb')->getMongoClient();
            $database = $client->selectDatabase('bembang_hotel');
            $transactionsCollection = $database->selectCollection('transactions');

            // Get date range from request or default to last 30 days
            $startDate = $request->input('start_date') 
                ? Carbon::parse($request->input('start_date')) 
                : Carbon::now()->subDays(29)->startOfDay();
            $endDate = $request->input('end_date') 
                ? Carbon::parse($request->input('end_date')) 
                : Carbon::now()->endOfDay();

            // Validate date range
            if ($startDate->gt($endDate)) {
                throw new \Exception('Start date must be before or equal to end date.');
            }

            // Convert to UTCDateTime for MongoDB
            $startDateMongo = new UTCDateTime($startDate->timestamp * 1000);
            $endDateMongo = new UTCDateTime($endDate->timestamp * 1000);

            // Get counts within date range
            $bookingCount = $transactionsCollection->countDocuments([
                'transaction_type' => 'Booking',
                'current_status' => ['$nin' => ['pending', 'cancelled', 'refunded']],
                'created_at' => ['$gte' => $startDateMongo, '$lte' => $endDateMongo]
            ]);
            
            $reservationCount = $transactionsCollection->countDocuments([
                'transaction_type' => 'Reservation',
                'current_status' => ['$nin' => ['pending', 'cancelled', 'refunded']],
                'created_at' => ['$gte' => $startDateMongo, '$lte' => $endDateMongo]
            ]);
            
            $activeTransactionsCount = $transactionsCollection->countDocuments([
                'current_status' => ['$in' => ['confirmed', 'completed']],
                'created_at' => ['$gte' => $startDateMongo, '$lte' => $endDateMongo]
            ]);
            
            $totalTransactionsCount = $transactionsCollection->countDocuments([
                'created_at' => ['$gte' => $startDateMongo, '$lte' => $endDateMongo]
            ]);

            // Compute percentages safely
            $bookingPercentage = $totalTransactionsCount > 0 
                ? round(($bookingCount / $totalTransactionsCount) * 100, 2)
                : 0;
            
            $reservationPercentage = $totalTransactionsCount > 0
                ? round(($reservationCount / $totalTransactionsCount) * 100, 2)
                : 0;

            // Calculate total revenue with modified approach to avoid undefined array key issues
            $totalRevenue = 0;
            
            try {
                $revenueResult = $transactionsCollection->aggregate([
                    [
                        '$match' => [
                            'current_status' => ['$nin' => ['pending', 'cancelled', 'refunded']],
                            'created_at' => ['$gte' => $startDateMongo, '$lte' => $endDateMongo]
                        ]
                    ],
                    [
                        '$unwind' => [
                            'path' => '$payments',
                            'preserveNullAndEmptyArrays' => false  // Only include docs with payments
                        ]
                    ],
                    [
                        '$group' => [
                            '_id' => null,
                            'total_revenue' => [
                                '$sum' => '$payments.amount'
                            ]
                        ]
                    ]
                ])->toArray();
                
                Log::debug('Revenue query result: ' . json_encode($revenueResult));
                
                // Check if we got any results back
                if (!empty($revenueResult) && isset($revenueResult[0]) && isset($revenueResult[0]['total_revenue'])) {
                    $totalRevenue = $revenueResult[0]['total_revenue'];
                } else {
                    Log::info('No revenue data found or structure is different than expected.');
                }
            } catch (\Exception $revenueException) {
                Log::error('Revenue calculation error: ' . $revenueException->getMessage());
                // Continue with the rest of the method - don't let revenue calculation failure stop everything
            }
            
            // Get all transactions within date range
            $transactions = $transactionsCollection->find([
                'created_at' => ['$gte' => $startDateMongo, '$lte' => $endDateMongo]
            ], [
                'sort' => ['created_at' => -1],
                'limit' => 100
            ])->toArray();
            
            // Initialize room type counts array
            $roomTypeCounts = [];
            
            // Process transactions to incorporate room details
            foreach ($transactions as &$transaction) {
                Log::info("Processing transaction with room_id: " . ($transaction['room_id'] ?? 'null'));
                
                try {
                    if (!empty($transaction['room_id'])) {
                        $roomId = new ObjectId($transaction['room_id']);
                        $idFinder = Rooms::getTypeId((string)$roomId);
                        
                        if ($idFinder) {
                            $room = MongoRoomType::findSpecificRoom((string)$idFinder);
                            
                            if ($room) {
                                // Convert BSONDocument to array properly
                                $transaction['room_details'] = json_decode(json_encode($room), true);
                                
                                // Count by room type
                                $roomTypeName = $transaction['room_details']['type_name'] ?? 'Unknown';
                                $roomTypeCounts[$roomTypeName] = ($roomTypeCounts[$roomTypeName] ?? 0) + 1;
                            } else {
                                Log::warning("Room type not found for ID: " . $idFinder);
                                $transaction['room_details'] = null;
                            }
                        } else {
                            Log::warning("No room type ID found for room: " . $transaction['room_id']);
                            $transaction['room_details'] = null;
                        }
                    } else {
                        Log::info("Transaction has no room_id");
                        $transaction['room_details'] = null;
                    }
                } catch (\Exception $roomException) {
                    Log::error('Error processing room for transaction: ' . $roomException->getMessage(), [
                        'transaction_id' => $transaction['_id'] ?? null,
                        'room_id' => $transaction['room_id'] ?? null,
                        'exception' => $roomException
                    ]);
                    $transaction['room_details'] = null;
                }
            }
            unset($transaction); // Unset reference to avoid side effects
            
            // Calculate chart data for room occupancy 
            $days = [];
            $chartDatasets = [];
            $occupiedByRoomType = [];
            
            // Get room types (ensure we have room types before proceeding)
            $roomTypes = !empty($roomTypeCounts) ? array_keys($roomTypeCounts) : [];
            
            if (!empty($roomTypes)) {
                // Initialize room type arrays
                foreach ($roomTypes as $roomType) {
                    $occupiedByRoomType[$roomType] = [];
                }
                
                // Generate daily data
                $period = CarbonPeriod::create($startDate->startOfDay(), '1 day', $endDate->endOfDay());
                foreach ($period as $day) {
                    $dayLabel = $day->format('Y-m-d');
                    $days[] = $dayLabel;
                    
                    $startOfDay = new UTCDateTime($day->startOfDay()->timestamp * 1000);
                    $endOfDay = new UTCDateTime($day->endOfDay()->timestamp * 1000);
                    
                    try {
                        // Count occupied rooms per room type for this day
                        $dailyTransactions = $transactionsCollection->find([
                            'current_status' => 'completed',
                            'stay_details.actual_checkin' => ['$lte' => $endOfDay],
                            'stay_details.actual_checkout' => ['$gte' => $startOfDay]
                        ])->toArray();
                        
                        // Reset counts for this day
                        $dailyCounts = array_fill_keys($roomTypes, 0);
                        
                        // Process daily transactions
                        foreach ($dailyTransactions as $transaction) {
                            $roomId = $transaction['room_id'] ?? null;
                            if ($roomId) {
                                $room = MongoRoomType::getSpecificRoom($roomId);
                                if ($room && isset($room->type_name)) {
                                    $roomTypeName = $room->type_name;
                                    if (in_array($roomTypeName, $roomTypes)) {
                                        $dailyCounts[$roomTypeName]++;
                                    }
                                }
                            }
                        }
                        
                        // Store counts for each room type
                        foreach ($roomTypes as $roomType) {
                            $occupiedByRoomType[$roomType][] = $dailyCounts[$roomType] ?? 0;
                        }
                    } catch (\Exception $dailyException) {
                        Log::error('Error calculating daily data: ' . $dailyException->getMessage());
                        // Add zeros for this day to maintain array structure
                        foreach ($roomTypes as $roomType) {
                            $occupiedByRoomType[$roomType][] = 0;
                        }
                    }
                }
                
                // Prepare chart datasets
                $colors = [
                    'rgba(75, 192, 192, 1)',   // Teal
                    'rgba(153, 102, 255, 1)',  // Purple
                    'rgba(255, 159, 64, 1)',   // Orange
                    'rgba(255, 99, 132, 1)',   // Red
                    'rgba(54, 162, 235, 1)',   // Blue
                ];
                
                $colorIndex = 0;
                foreach ($roomTypes as $roomType) {
                    $chartDatasets[] = [
                        'label' => $roomType,
                        'data' => $occupiedByRoomType[$roomType],
                        'borderColor' => $colors[$colorIndex % count($colors)],
                        'backgroundColor' => str_replace('1)', '0.2)', $colors[$colorIndex % count($colors)]),
                        'fill' => false,
                        'tension' => 0.1
                    ];
                    $colorIndex++;
                }
            }
            
            // Prepare response data
            $responseData = [
                'metrics' => [
                    'booking_count' => $bookingCount,
                    'reservation_count' => $reservationCount,
                    'active_transactions_count' => $activeTransactionsCount,
                    'total_transactions_count' => $totalTransactionsCount,
                    'booking_percentage' => $bookingPercentage,
                    'reservation_percentage' => $reservationPercentage,
                    'total_revenue' => $totalRevenue,
                    'room_type_counts' => $roomTypeCounts
                ],
                'chart_data' => [
                    'labels' => $days,
                    'datasets' => $chartDatasets
                ],
                'transactions' => $transactions,
                'start_date' => $startDate->toDateString(),
                'end_date' => $endDate->toDateString()
            ];
            
            return view('management.performance', $responseData);

        } catch (\Exception $e) {
            // Log the full error with stack trace for better debugging
            Log::error('Performance data error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return view('management.performance', [
                'error' => 'Failed to load performance data: ' . $e->getMessage(),
                'metrics' => [
                    'booking_count' => 0,
                    'reservation_count' => 0,
                    'active_transactions_count' => 0,
                    'total_transactions_count' => 0,  
                    'booking_percentage' => 0,
                    'reservation_percentage' => 0,
                    'total_revenue' => 0,
                    'room_type_counts' => []
                ],
                'chart_data' => [
                    'labels' => [],
                    'datasets' => []
                ],
                'transactions' => [],
                'start_date' => $request->input('start_date', Carbon::now()->subDays(29)->toDateString()),
                'end_date' => $request->input('end_date', Carbon::now()->toDateString())
            ]);
        }
    }
    public function getPerformanceDashboard(Request $request)
    {
        try {
            $client = DB::connection('mongodb')->getMongoClient();
            $database = $client->selectDatabase('bembang_hotel');
            $transactionsCollection = $database->selectCollection('transactions');

            // Get date range from request or default to last 30 days
            $startDate = $request->input('start_date') 
                ? Carbon::parse($request->input('start_date')) 
                : Carbon::now()->subDays(29)->startOfDay();
            $endDate = $request->input('end_date') 
                ? Carbon::parse($request->input('end_date')) 
                : Carbon::now()->endOfDay();

            // Validate date range
            if ($startDate->gt($endDate)) {
                throw new \Exception('Start date must be before or equal to end date.');
            }

            // Convert to UTCDateTime for MongoDB
            $startDateMongo = new UTCDateTime($startDate->timestamp * 1000);
            $endDateMongo = new UTCDateTime($endDate->timestamp * 1000);

            // Get counts within date range
            $bookingCount = $transactionsCollection->countDocuments([
                'transaction_type' => 'Booking',
                'current_status' => ['$nin' => ['pending', 'cancelled', 'refunded']],
                'created_at' => ['$gte' => $startDateMongo, '$lte' => $endDateMongo]
            ]);
            
            $reservationCount = $transactionsCollection->countDocuments([
                'transaction_type' => 'Reservation',
                'current_status' => ['$nin' => ['pending', 'cancelled', 'refunded']],
                'created_at' => ['$gte' => $startDateMongo, '$lte' => $endDateMongo]
            ]);
            
            $activeTransactionsCount = $transactionsCollection->countDocuments([
                'current_status' => ['$in' => ['confirmed', 'completed']],
                'created_at' => ['$gte' => $startDateMongo, '$lte' => $endDateMongo]
            ]);
            
            $totalTransactionsCount = $transactionsCollection->countDocuments([
                'created_at' => ['$gte' => $startDateMongo, '$lte' => $endDateMongo]
            ]);

            // Compute percentages safely
            $bookingPercentage = $totalTransactionsCount > 0 
                ? round(($bookingCount / $totalTransactionsCount) * 100, 2)
                : 0;
            
            $reservationPercentage = $totalTransactionsCount > 0
                ? round(($reservationCount / $totalTransactionsCount) * 100, 2)
                : 0;

            // Calculate total revenue with modified approach to avoid undefined array key issues
            $totalRevenue = 0;
            
            try {
                $revenueResult = $transactionsCollection->aggregate([
                    [
                        '$match' => [
                            'current_status' => ['$nin' => ['pending', 'cancelled', 'refunded']],
                            'created_at' => ['$gte' => $startDateMongo, '$lte' => $endDateMongo]
                        ]
                    ],
                    [
                        '$unwind' => [
                            'path' => '$payments',
                            'preserveNullAndEmptyArrays' => false  // Only include docs with payments
                        ]
                    ],
                    [
                        '$group' => [
                            '_id' => null,
                            'total_revenue' => [
                                '$sum' => '$payments.amount'
                            ]
                        ]
                    ]
                ])->toArray();
                
                Log::debug('Revenue query result: ' . json_encode($revenueResult));
                
                // Check if we got any results back
                if (!empty($revenueResult) && isset($revenueResult[0]) && isset($revenueResult[0]['total_revenue'])) {
                    $totalRevenue = $revenueResult[0]['total_revenue'];
                } else {
                    Log::info('No revenue data found or structure is different than expected.');
                }
            } catch (\Exception $revenueException) {
                Log::error('Revenue calculation error: ' . $revenueException->getMessage());
                // Continue with the rest of the method - don't let revenue calculation failure stop everything
            }
            
            // Get all transactions within date range
            $transactions = $transactionsCollection->find([
                'created_at' => ['$gte' => $startDateMongo, '$lte' => $endDateMongo]
            ], [
                'sort' => ['created_at' => -1],
                'limit' => 100
            ])->toArray();
            
            // Initialize room type counts array
            $roomTypeCounts = [];
            
            // Process transactions to incorporate room details
            foreach ($transactions as &$transaction) {
                Log::info("Processing transaction with room_id: " . ($transaction['room_id'] ?? 'null'));
                
                try {
                    if (!empty($transaction['room_id'])) {
                        $roomId = new ObjectId($transaction['room_id']);
                        $idFinder = Rooms::getTypeId((string)$roomId);
                        
                        if ($idFinder) {
                            $room = MongoRoomType::findSpecificRoom((string)$idFinder);
                            
                            if ($room) {
                                // Convert BSONDocument to array properly
                                $transaction['room_details'] = json_decode(json_encode($room), true);
                                
                                // Count by room type
                                $roomTypeName = $transaction['room_details']['type_name'] ?? 'Unknown';
                                $roomTypeCounts[$roomTypeName] = ($roomTypeCounts[$roomTypeName] ?? 0) + 1;
                            } else {
                                Log::warning("Room type not found for ID: " . $idFinder);
                                $transaction['room_details'] = null;
                            }
                        } else {
                            Log::warning("No room type ID found for room: " . $transaction['room_id']);
                            $transaction['room_details'] = null;
                        }
                    } else {
                        Log::info("Transaction has no room_id");
                        $transaction['room_details'] = null;
                    }
                } catch (\Exception $roomException) {
                    Log::error('Error processing room for transaction: ' . $roomException->getMessage(), [
                        'transaction_id' => $transaction['_id'] ?? null,
                        'room_id' => $transaction['room_id'] ?? null,
                        'exception' => $roomException
                    ]);
                    $transaction['room_details'] = null;
                }
            }
            unset($transaction); // Unset reference to avoid side effects
            
            // Calculate chart data for room occupancy 
            $days = [];
            $chartDatasets = [];
            $occupiedByRoomType = [];
            
            // Get room types (ensure we have room types before proceeding)
            $roomTypes = !empty($roomTypeCounts) ? array_keys($roomTypeCounts) : [];
            
            if (!empty($roomTypes)) {
                // Initialize room type arrays
                foreach ($roomTypes as $roomType) {
                    $occupiedByRoomType[$roomType] = [];
                }
                
                // Generate daily data
                $period = CarbonPeriod::create($startDate->startOfDay(), '1 day', $endDate->endOfDay());
                foreach ($period as $day) {
                    $dayLabel = $day->format('Y-m-d');
                    $days[] = $dayLabel;
                    
                    $startOfDay = new UTCDateTime($day->startOfDay()->timestamp * 1000);
                    $endOfDay = new UTCDateTime($day->endOfDay()->timestamp * 1000);
                    
                    try {
                        // Count occupied rooms per room type for this day
                        $dailyTransactions = $transactionsCollection->find([
                            'current_status' => 'completed',
                            'stay_details.actual_checkin' => ['$lte' => $endOfDay],
                            'stay_details.actual_checkout' => ['$gte' => $startOfDay]
                        ])->toArray();
                        
                        // Reset counts for this day
                        $dailyCounts = array_fill_keys($roomTypes, 0);
                        
                        // Process daily transactions
                        foreach ($dailyTransactions as $transaction) {
                            $roomId = $transaction['room_id'] ?? null;
                            if ($roomId) {
                                $room = MongoRoomType::getSpecificRoom($roomId);
                                if ($room && isset($room->type_name)) {
                                    $roomTypeName = $room->type_name;
                                    if (in_array($roomTypeName, $roomTypes)) {
                                        $dailyCounts[$roomTypeName]++;
                                    }
                                }
                            }
                        }
                        
                        // Store counts for each room type
                        foreach ($roomTypes as $roomType) {
                            $occupiedByRoomType[$roomType][] = $dailyCounts[$roomType] ?? 0;
                        }
                    } catch (\Exception $dailyException) {
                        Log::error('Error calculating daily data: ' . $dailyException->getMessage());
                        // Add zeros for this day to maintain array structure
                        foreach ($roomTypes as $roomType) {
                            $occupiedByRoomType[$roomType][] = 0;
                        }
                    }
                }
                
                // Prepare chart datasets
                $colors = [
                    'rgba(75, 192, 192, 1)',   // Teal
                    'rgba(153, 102, 255, 1)',  // Purple
                    'rgba(255, 159, 64, 1)',   // Orange
                    'rgba(255, 99, 132, 1)',   // Red
                    'rgba(54, 162, 235, 1)',   // Blue
                ];
                
                $colorIndex = 0;
                foreach ($roomTypes as $roomType) {
                    $chartDatasets[] = [
                        'label' => $roomType,
                        'data' => $occupiedByRoomType[$roomType],
                        'borderColor' => $colors[$colorIndex % count($colors)],
                        'backgroundColor' => str_replace('1)', '0.2)', $colors[$colorIndex % count($colors)]),
                        'fill' => false,
                        'tension' => 0.1
                    ];
                    $colorIndex++;
                }
            }
            $rooms = Rooms::getAll();
            $roomCounts = [
                'available'   => $rooms->where('status', 'available')->count(),
                'occupied'    => $rooms->where('status', 'occupied')->count(),
                'maintenance' => $rooms->where('status', 'maintenance')->count(),
                'cleaning'    => $rooms->where('status', 'cleaning')->count(),
            ];
    
            // Prepare response data
            Log::info($roomCounts);
            $responseData = [
                'metrics' => [
                    'booking_count' => $bookingCount,
                    'reservation_count' => $reservationCount,
                    'active_transactions_count' => $activeTransactionsCount,
                    'total_transactions_count' => $totalTransactionsCount,
                    'booking_percentage' => $bookingPercentage,
                    'reservation_percentage' => $reservationPercentage,
                    'total_revenue' => $totalRevenue,
                    'room_type_counts' => $roomTypeCounts,
                    'room_status_count' => $roomCounts,
                ],
                'chart_data' => [
                    'labels' => $days,
                    'datasets' => $chartDatasets
                ],
                'transactions' => $transactions,
                'start_date' => $startDate->toDateString(),
                'end_date' => $endDate->toDateString()
            ];
            
            return view('management.dashboard', $responseData);

        } catch (\Exception $e) {
            // Log the full error with stack trace for better debugging
            Log::error('Performance data error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return view('management.dashboard', [
                'error' => 'Failed to load performance data: ' . $e->getMessage(),
                'metrics' => [
                    'booking_count' => 0,
                    'reservation_count' => 0,
                    'active_transactions_count' => 0,
                    'total_transactions_count' => 0,  
                    'booking_percentage' => 0,
                    'reservation_percentage' => 0,
                    'total_revenue' => 0,
                    'room_type_counts' => []
                ],
                'chart_data' => [
                    'labels' => [],
                    'datasets' => []
                ],
                'transactions' => [],
                'start_date' => $request->input('start_date', Carbon::now()->subDays(29)->toDateString()),
                'end_date' => $request->input('end_date', Carbon::now()->toDateString())
            ]);
        }
    }
    public function getMyProfile()
    {
        return view('management.myprofile');
    }
}