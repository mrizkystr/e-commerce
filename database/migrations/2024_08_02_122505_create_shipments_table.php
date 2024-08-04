<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShipmentsTable extends Migration
{
    public function up()
    {
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->string('province_id');
            $table->string('city_id');
            $table->string('district_id');
            $table->string('village_id');
            $table->string('postal_code');
            $table->string('country');
            $table->string('status')->default('pending');
            $table->timestamps();

            $table->foreignId('users_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('shipments');
    }
}
