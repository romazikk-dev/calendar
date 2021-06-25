<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('hall_id');
            $table->unsignedBigInteger('worker_id');
            $table->unsignedBigInteger('template_id');
            $table->unsignedBigInteger('client_id');
            $table->dateTime('time');
            $table->unsignedInteger('custom_duration')->nullable();
            $table->tinyInteger('approved')->default(0);
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('hall_id')->references('id')->on('halls');
            $table->foreign('worker_id')->references('id')->on('workers');
            $table->foreign('template_id')->references('id')->on('templates');
            $table->foreign('client_id')->references('id')->on('clients');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bookings');
    }
}