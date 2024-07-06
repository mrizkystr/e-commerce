<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Merchant extends Model
{
    use HasFactory;

    protected $table = 'merchants';

    protected $fillable = ['country_code', 'merchant_name'];

    public $incrementing = false;
    protected $primaryKey = ['id', 'country_code'];
    public $timestamps = true;
}

