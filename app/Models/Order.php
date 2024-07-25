<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'users_id',
        'cart_items_id',
        'total_price',
        'status',
        'transaction_status',
    ];

    /**
     * Get the user that owns the order.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    /**
     * Get the cart item associated with the order.
     */
    public function cartItem()
    {
        return $this->belongsTo(CartItem::class, 'cart_items_id');
    }
}
