<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model {
    use HasFactory;

    protected $fillable = [
        'users_id',
        'products_id',
        'order_id'
    ];

    public function user() {
        return $this->belongsTo(User::class, 'users_id');
    }

    public function product() {
        return $this->belongsTo(Product::class, 'products_id');
    }

    public function order() {
        return $this->belongsTo(Order::class);
    }
}
