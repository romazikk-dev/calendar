<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHallWorkerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hall_worker', function (Blueprint $table) {
            $table->unsignedBigInteger('worker_id');
            $table->unsignedBigInteger('hall_id');
            
            // $table->unique('worker_id', 'hall_id');
            $table->foreign('worker_id')->references('id')->on('workers');
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
        Schema::dropIfExists('hall_worker');
    }
}
