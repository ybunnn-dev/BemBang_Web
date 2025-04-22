<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use MongoDB\Laravel\Relations\BelongsTo;

class Voucher extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'vouchers';

    // Define voucher types as constants
    const TYPE_BUYABLE = 'buyable';
    const TYPE_CONDITIONAL = 'conditional';
    const TYPE_AUTOMATIC = 'automatic';

    // Define value types
    const VALUE_TYPE_FIXED = 'fixed';
    const VALUE_TYPE_PERCENTAGE = 'percentage';

    protected $fillable = [
        'name',
        'description',
        'type',
        'value_type',
        'value',
        'price',            // For buyable vouchers
        'conditions',        // For conditional vouchers
        'membership_required',
        'valid_from',
        'valid_until',
        'is_active'
    ];

    protected $casts = [
        'value' => 'integer',
        'price' => 'integer',
        'membership_required' => 'object',
        'valid_from' => 'datetime',
        'valid_until' => 'datetime',
        'is_active' => 'boolean',
        'conditions' => 'array',
    ];

    // Relationship to Membership
    public function requiredMembership(): BelongsTo
    {
        return $this->belongsTo(Membership::class, 'membership_required');
    }

    // Helper method to check if voucher is buyable
    public function isBuyable(): bool
    {
        return $this->type === self::TYPE_BUYABLE;
    }

    // Helper method to check if voucher is conditional
    public function isConditional(): bool
    {
        return $this->type === self::TYPE_CONDITIONAL;
    }

    // Calculate discount amount based on voucher type
    public function calculateDiscount(float $originalAmount): float
    {
        if ($this->value_type === self::VALUE_TYPE_FIXED) {
            return min($this->value, $originalAmount);
        }

        // Percentage discount
        return $originalAmount * ($this->value / 100);
    }

    // Check if conditions are met (for conditional vouchers)
    public function checkConditions(array $guestData): bool
    {
        if (!$this->isConditional()) {
            return true;
        }

        foreach ($this->conditions as $key => $requiredValue) {
            if (!isset($guestData[$key]) || $guestData[$key] < $requiredValue) {
                return false;
            }
        }

        return true;
    }
}