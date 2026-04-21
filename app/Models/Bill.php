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
        'usage_units',
        'consumption',
        'base_charge',
        'usage_charge',
        'additional_charge_amount',
        'additional_charge_note',
        'applied_additional_charges',
        'total_amount',
        'status',
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
        return $this->belongsTo(Customer::class);
    }
}
