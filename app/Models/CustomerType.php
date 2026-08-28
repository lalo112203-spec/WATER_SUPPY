<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerType extends Model
{
    protected $fillable = [
        'name',
        'base_charge',
        'usage_rate',
        'green_max',
        'orange_max',
        'red_max',
        'base_limit',
    ];

    public function customers()
    {
        return $this->hasMany(Customer::class);
    }
}
