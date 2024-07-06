<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'merchants_id',
        'price',
        'status',
    ];

    public function merchant()
    {
        return $this->belongsTo(Merchant::class); //--> belum ada table merchant
    }
}