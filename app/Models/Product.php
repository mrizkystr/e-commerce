<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'merchants_id',
        'price',
        'status',
        'image',
    ];

    public function merchant()
    {
        return $this->belongsTo(Merchant::class, 'merchants_id', 'id');
    }
}
