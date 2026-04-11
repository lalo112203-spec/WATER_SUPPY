<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegistrationCode extends Model
{
    protected $fillable = [
        'code',
        'is_used',
        'used_by',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'used_by');
    }
}
