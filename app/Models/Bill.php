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
        'total_amount',
        'status',
        'due_date',
        'paid_date',
    ];

    protected $casts = [
        'billing_date' => 'date',
        'due_date' => 'date',
        'paid_date' => 'date',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
