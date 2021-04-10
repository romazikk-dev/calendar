<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkersSuspensionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workers_suspensions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('worker_id')->unique();
            $table->date('from')->nullable();
            $table->date('to')->nullable();
            $table->timestamps();
            
            $table->foreign('worker_id')->references('id')->on('workers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('workers_suspensions');
    }
}
