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
        'email',
        'address',
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
        return $this->hasOne(User::class);
    }
}
