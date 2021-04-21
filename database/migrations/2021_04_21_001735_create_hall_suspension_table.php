<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHallSuspensionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hall_suspension', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hall_id')->unique();
            $table->date('from')->nullable();
            $table->date('to')->nullable();
            $table->timestamps();
            
            $table->foreign('hall_id')->references('id')->on('halls');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hall_suspension');
    }
}
