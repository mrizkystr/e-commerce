<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixOrdersForeignKeyConstraint extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Hapus foreign key constraint yang salah
            $table->dropForeign(['cart_items_id']);
            
            // Tambahkan foreign key constraint yang benar
            $table->foreign('cart_items_id')->references('id')->on('cart_items')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Hapus foreign key constraint yang benar
            $table->dropForeign(['cart_items_id']);
            
            // Tambahkan foreign key constraint yang salah kembali (untuk rollback)
            $table->foreign('cart_items_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
}
