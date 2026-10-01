<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'admin_id',
        'customer_id',
        'name',
        'type',
        'customer_type_id',
        'email',
        'meter_post',
        'phone_number',
        'address',
        'barangay',
        'meter_reading',
        'total_consumption',
        'status',
    ];

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function waterUsages(): HasMany
    {
        return $this->hasMany(WaterUsage::class);
    }

    public function bills(): HasMany
    {
        return $this->hasMany(Bill::class);
    }

    public function user()
    {
        return $this->hasOne(User::class)->withTrashed();
    }

    public function customerType()
    {
        return $this->belongsTo(CustomerType::class);
    }

    public function getUnpaidBillsCountAttribute(): int
    {
        return $this->bills()->whereNotIn('status', ['Paid', 'paid'])->count();
    }

    public function getUnpaidBillsTotalAttribute(): float
    {
        return (float) $this->bills()->whereNotIn('status', ['Paid', 'paid'])->sum('total_amount');
    }

    public function isEligibleForDisconnection(): bool
    {
        $threshold = (int) SystemSetting::get('disconnection_unpaid_months', 4);
        return $this->unpaid_bills_count >= $threshold;
    }

    public function isFirstReading(): bool
    {
        return $this->bills()->count() === 0;
    }
}
