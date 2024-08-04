<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    protected $fillable = [
        'users_id', 
        'province_id',
        'city_id',
        'district_id',
        'village_id',
        'postal_code',
        'country',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
}
