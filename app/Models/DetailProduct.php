<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailProduct extends Model
{
    use HasFactory;

    protected $table = 'detail_products';

    // Pastikan nama atribut yang bisa diisi dengan massal benar
    protected $fillable = [
        'product_id',
        'description',
    ];

    // Definisikan relasi ke model Product
    public function product() 
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
