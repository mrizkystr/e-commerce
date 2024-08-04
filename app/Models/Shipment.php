<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    protected $fillable = [
        'users_id', // Use 'users_id'
        'address',
        'city',
        'state',
        'postal_code',
        'country',
        'status',
    ];

    // If you have relationship defined
    public function user()
    {
        return $this->belongsTo(User::class, 'users_id'); // Match column name
    }
}


