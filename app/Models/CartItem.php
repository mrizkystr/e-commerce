<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'carts_id',
        'products_id',
        'quantity'
    ];

    public function cart()
    {
        return $this->belongsTo(Cart::class, 'carts_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'products_id');
    }
}
