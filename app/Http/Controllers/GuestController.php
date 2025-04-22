<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use App\Models\Guest;
use App\Models\Transaction;
use App\Models\Voucher;
use Illuminate\Http\Request;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class GuestController extends Controller
{
    public function getGuests()
    {
        try {
            $guests = Guest::allGuests();
            Log::info("Guests retrieved", ['count' => $guests->count()]);

            // Attach transactions and vouchers to guests
            $guests->each(function ($guest) {
                // Convert _id to string if it's an ObjectId
                $guestId = $guest->_id instanceof ObjectId ? (string) $guest->_id : (string) $guest->_id;
                Log::debug('Fetching transactions for guest', ['guest_id' => $guestId]);
                $guest->transaction_details = $this->getGuestTransactions($guestId);
                $guest->voucher_details = [];
                $guest->last_checkin = $this->GetRecentCheckin($guest->transaction_details);
                $guest->address = $guest->address ?? 'Not provided';
            });

            // Collect all unique voucher IDs
            $allVoucherIds = $guests->flatMap(function ($guest) {
                return collect($guest->user_vouchers ?? [])
                    ->map(function ($userVoucher) {
                        return $this->normalizeVoucherId($userVoucher['voucher_id'] ?? null);
                    })
                    ->filter();
            })->unique()->values();

            Log::debug('Extracted Voucher IDs', ['voucher_ids' => $allVoucherIds->toArray()]);
            Log::info("Voucher IDs processed");

            // Get all vouchers in one query
            $vouchers = $allVoucherIds->isNotEmpty() ? Voucher::whereIn('_id', $allVoucherIds)->get()->keyBy('_id') : collect();

            // Attach vouchers to guests
            $guests->each(function ($guest) use ($vouchers) {
                $guest->voucher_details = collect($guest->user_vouchers ?? [])->map(function ($userVoucher) use ($vouchers) {
                    return [
                        'voucher_details' => $vouchers->get($this->normalizeVoucherId($userVoucher['voucher_id'] ?? null)),
                        'status' => $userVoucher['status'] ?? null,
                        'date_claimed' => $userVoucher['date_claimed'] ?? null,
                        'date_expired' => $userVoucher['date_expired'] ?? null,
                    ];
                })->toArray();
            });

            // Log the final data
            Log::channel('single')->info("Final Aggregated Guest Data:", [
                'timestamp' => now()->toDateTimeString(),
                'data' => $guests->toArray()
            ]);

            return response()->json($guests);
        } catch (\Exception $e) {
            Log::error('Failed to fetch guests', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to fetch guests'], 500);
        }
    }

    /**
     * Fetch transactions for a guest by guest_id and log details.
     *
     * @param string $guestId
     * @return array
     */
    protected function getGuestTransactions($guestId)
    {
        // Log the input guestId
        Log::debug('Starting getGuestTransactions', [
            'guest_id' => $guestId,
        ]);

        // Fetch transactions
        $yahoo = Transaction::getTransactPerGuest($guestId);

        // Log the retrieved transactions
        Log::debug('Transactions retrieved for guest', [
            'guest_id' => $guestId,
            'transaction_count' => count($yahoo),
            'transactions' => $yahoo,
        ]);

        return $yahoo;
    }

    /**
     * Determine the most recent actual_checkin date from transaction_details.
     *
     * @param array $transactionDetails
     * @return string
     */
    protected function GetRecentCheckin(array $transactionDetails)
    {
        // If no transactions, return "Never"
        if (empty($transactionDetails)) {
            Log::debug('No transactions found for recent check-in', ['transaction_count' => 0]);
            return 'Never';
        }

        // Collect all non-null actual_checkin dates
        $checkinDates = collect($transactionDetails)
            ->filter(function ($transaction) {
                return isset($transaction['stay_details']['actual_checkin'])
                    && !is_null($transaction['stay_details']['actual_checkin']);
            })
            ->map(function ($transaction) {
                $checkin = $transaction['stay_details']['actual_checkin'];
                // Handle UTCDateTime for robustness
                if ($checkin instanceof UTCDateTime) {
                    return $checkin->toDateTime()->format('Y-m-d H:i:s');
                }
                return $checkin; // Assume it's a string
            })
            ->filter();

        // If no valid check-in dates, return "Never"
        if ($checkinDates->isEmpty()) {
            Log::debug('No valid actual_checkin dates found', ['transaction_count' => count($transactionDetails)]);
            return 'Never';
        }

        // Find the most recent check-in date
        $latestCheckin = $checkinDates->sortByDesc(function ($date) {
            return strtotime($date);
        })->first();

        Log::debug('Recent check-in determined', [
            'latest_checkin' => $latestCheckin,
            'checkin_count' => $checkinDates->count(),
        ]);

        // Return "Recent" or the actual date
        return $latestCheckin; // Or return $latestCheckin for the actual date
    }

    /**
     * Normalize MongoDB ObjectId to string.
     *
     * @param mixed $id
     * @return string|null
     */
    protected function normalizeVoucherId($id)
    {
        if (!$id) {
            return null;
        }

        if (is_array($id) && isset($id['$oid'])) {
            return $id['$oid'];
        }

        if (is_object($id) && method_exists($id, '__toString')) {
            return (string) $id;
        }

        return (string) $id;
    }
}