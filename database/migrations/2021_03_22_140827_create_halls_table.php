<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHallsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('halls', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('title');
        	$table->string('description', 1000)->nullable();
        	$table->string('short_description')->nullable();
        	$table->string('notice', 1000)->nullable();
            $table->string('country')->nullable();
            $table->string('town')->nullable();
            $table->string('street')->nullable();
            $table->tinyInteger('is_closed')->default(0);
        	$table->json('business_hours')->nullable();
            $table->tinyInteger('is_deleted')->default(0);
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('halls');
    }
}
