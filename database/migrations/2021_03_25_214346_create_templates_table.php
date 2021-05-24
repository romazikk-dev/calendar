<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('templates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('specific_id')->nullable();
            $table->string('title');
            $table->decimal('price', $precision = 8, $scale = 2)->nullable();
        	$table->string('description', 1000)->nullable();
        	$table->string('short_description')->nullable();
        	$table->string('notice', 1000)->nullable();
            $table->unsignedInteger('duration');
            $table->tinyInteger('is_deleted')->default(0);
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('specific_id')->references('id')->on('template_specifics');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('templates');
    }
}
