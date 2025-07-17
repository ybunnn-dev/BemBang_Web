<?php

namespace App\Models;

use MongoDB\Client;
use MongoDB\BSON\ObjectId;
use Illuminate\Support\Facades\DB;  
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;
use App\Models\Guest;

class Reviews
{
    public static function getReviews($roomTypeId): Collection
    {
        try {
            // Get MongoDB client
            $client = DB::connection('mongodb')->getMongoClient();
            
            // Select database and collection
            $database = $client->selectDatabase('bembang_hotel');
            $collection = $database->selectCollection('reviews');

            // Convert ID to ObjectId if needed
            $roomObjectId = self::normalizeId($roomTypeId);

            // Build query
            $query = [
                'room_type' => $roomObjectId,
                'status' => 'published'
            ];

            // Query options (sorting)
            $options = [
                'sort' => ['created_at' => -1] // -1 = DESC
            ];

            // Execute query
            $cursor = $collection->find($query, $options);

            // Convert to array of results
            $results = iterator_to_array($cursor);

            // Process each review to include guest data
            $processedReviews = [];
            foreach ($results as $review) {
                try {
                    // Handle different guest_id formats
                    $guestId = is_object($review['guest_id']) 
                        ? (string)$review['guest_id'] 
                        : $review['guest_id']['$oid'] ?? $review['guest_id'];
                    
                    $guest = Guest::getSpecificGuest($guestId);
                    
                    $processedReviews[] = (object)[
                        '_id' => $review['_id'],
                        'rate' => $review['rate'],
                        'room_type' => $review['room_type'],
                        'guest_id' => $guest, // Entire guest object
                        'comment' => $review['comment'],
                        'status' => $review['status'],
                        'created_at' => $review['created_at'],
                        'updated_at' => $review['updated_at']
                    ];
                } catch (\Exception $e) {
                    Log::error("Error processing review guest data", [
                        'review_id' => (string)$review['_id'],
                        'guest_id' => $guestId ?? null,
                        'error' => $e->getMessage()
                    ]);
                    continue;
                }
            }

            return new Collection($processedReviews);

        } catch (\Exception $e) {
            Log::error("Review fetch error", [
                'room_id' => (string)$roomTypeId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return new Collection(); // Return empty collection on error
        }
    }
    protected static function normalizeId($id): ObjectId
    {
        if ($id instanceof ObjectId) {
            return $id;
        }
        
        if (is_array($id) && isset($id['$oid'])) {
            return new ObjectId($id['$oid']);
        }
        
        return new ObjectId($id);
    }
}