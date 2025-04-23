<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use MongoDB\Laravel\Relations\BelongsTo;
use Illuminate\Support\Facades\DB; 
use MongoDB\BSON\ObjectId;

class Guest extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'guests';

    protected $fillable = [
        'user_id',
        'membership_id',
        'firstName',
        'lastName',
        'mobileNumber',
        'email',
        'gender',
        'user_vouchers',
        'points',
        'checkin_count'
    ];

    protected $casts = [
        'user_id' => 'object',
        'membership_id' => 'object',
        'points' => 'integer',
        'checkin_count' => 'integer',
        'user_vouchers' => 'array',
    ];

    // Relationship to Membership
    public static function allGuests(){
        try {
            // Get the MongoDB client
            $client = DB::connection('mongodb')->getMongoClient();
            // Access the database and collection
            $database = $client->bembang_hotel;  // Your DB name
            $collection = $database->guests;  // Collection name

            // Fetch all documents from the collection
            $documents = $collection->find()->toArray();  // Use the find method for all documents

            // Return the documents as a collection or array
            return collect($documents);
        } catch (\Exception $e) {
            // Handle errors and log the issue
            Log::error("MongoDB query error: " . $e->getMessage());
            return collect([]);  // Return empty collection in case of error
        }
    }
    public function membership(): BelongsTo
    {
        return $this->belongsTo(Membership::class, 'membership_id');
    }

    /**
     * Add a voucher to the guest
     */
    public function addVoucher(Voucher $voucher, string $status = 'unused'): bool
    {
        $userVouchers = $this->user_vouchers ?? [];
        
        $userVouchers[] = [
            'voucher_id' => $voucher->_id,
            'status' => $status,
            'date_claimed' => null,
            'date_expired' => $voucher->valid_until,
            'type' => $voucher->type,
            'value_type' => $voucher->value_type,
            'value' => $voucher->value
        ];

        $this->user_vouchers = $userVouchers;
        return $this->save();
    }

    /**
     * Get all valid vouchers for this guest
     */
    public function getValidVouchers()
    {
        return collect($this->user_vouchers)
            ->filter(function ($voucher) {
                return $voucher['status'] === 'unused' && 
                       (empty($voucher['date_expired']) || now()->lt($voucher['date_expired']));
            });
    }
public static function getSpecificGuest($id){
    try {
        $client = DB::connection('mongodb')->getMongoClient();
        $database = $client->bembang_hotel;
        $collection = $database->guests;
        
        // Convert string ID to ObjectId if needed
        try {
            $objectId = new ObjectId($id);
            $document = $collection->findOne(['_id' => $objectId]);
        } catch (\Exception $e) {
            // If ID isn't valid ObjectId format, try as string
            $document = $collection->findOne(['_id' => $id]);
        }
        
        return $document ? (object)$document : null;
        
    } catch (\Exception $e) {
        Log::error("MongoDB find room error: " . $e->getMessage());
        return null;
    }
}

    /**
     * Check if guest qualifies for conditional vouchers
     */
    public function checkForConditionalVouchers()
    {
        $eligibleVouchers = Voucher::where('type', Voucher::TYPE_CONDITIONAL)
            ->where('is_active', true)
            ->where('valid_from', '<=', now())
            ->where('valid_until', '>=', now())
            ->get()
            ->filter(function ($voucher) {
                return $voucher->checkConditions([
                    'checkin_count' => $this->checkin_count,
                    'points' => $this->points
                ]);
            });

        foreach ($eligibleVouchers as $voucher) {
            // Check if guest already has this voucher
            $alreadyHas = collect($this->user_vouchers)
                ->contains('voucher_id', (string)$voucher->_id);

            if (!$alreadyHas) {
                $this->addVoucher($voucher);
            }
        }

        return $this;
    }

    /**
     * Redeem a voucher
     */
    public function redeemVoucher(string $voucherId, float $originalAmount = null): array
    {
        $userVouchers = $this->user_vouchers;
        $voucher = Voucher::find($voucherId);
        $discount = 0;

        foreach ($userVouchers as &$userVoucher) {
            if ((string)$userVoucher['voucher_id'] === $voucherId && $userVoucher['status'] === 'unused') {
                $userVoucher['status'] = 'used';
                $userVoucher['date_claimed'] = now();

                if ($originalAmount !== null) {
                    $discount = $voucher->calculateDiscount($originalAmount);
                }

                break;
            }
        }

        $this->user_vouchers = $userVouchers;
        $this->save();

        return [
            'success' => true,
            'discount_amount' => $discount,
            'voucher' => $voucher
        ];
    }
}