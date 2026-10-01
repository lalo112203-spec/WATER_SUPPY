<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bill extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'customer_id',
        'billing_date',
        'previous_reading',
        'new_reading',
        'usage_units',
        'consumption',
        'base_charge',
        'usage_charge',
        'additional_charge_amount',
        'additional_charge_note',
        'applied_additional_charges',
        'total_amount',
        'status',
        'or_number',
        'due_date',
        'paid_date',
    ];

    protected $casts = [
        'billing_date' => 'date',
        'due_date' => 'date',
        'paid_date' => 'date',
        'applied_additional_charges' => 'array',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class)->withTrashed();
    }

    /**
     * Compute total arrears (all other unpaid bills of this customer).
     */
    public function getArrearsAttribute(): float
    {
        if (!$this->customer_id) {
            return 0.0;
        }

        return (float) static::where('customer_id', $this->customer_id)
            ->where('id', '!=', $this->id)
            ->whereNotIn('status', ['Paid', 'paid'])
            ->sum('total_amount');
    }

    /**
     * Get official receipt number or fallback formatted receipt number.
     */
    public function getOrNumberDisplayAttribute(): string
    {
        if (!empty($this->or_number)) {
            return $this->or_number;
        }

        return 'OR-' . str_pad($this->id, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Check if this bill is the consumer's first bill.
     */
    public function isFirstBill(): bool
    {
        if (!$this->customer_id) {
            return false;
        }

        return (int) static::where('customer_id', $this->customer_id)
            ->orderBy('billing_date', 'asc')
            ->orderBy('id', 'asc')
            ->value('id') === (int) $this->id;
    }
}
