<?php

namespace App\Models;

use App\Models\CartItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'users_id',
        'cart_items_id',
        'total_price',
        'status',
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
