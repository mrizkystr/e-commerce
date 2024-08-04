<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    protected $fillable = [
        'users_id',
        'province',
        'city',
        'district',
        'neighborhoods',
        'postal_code',
        'country',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
}
