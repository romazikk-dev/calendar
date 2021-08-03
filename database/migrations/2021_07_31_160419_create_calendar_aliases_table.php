<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCalendarAliasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calendar_aliases', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id');
            $table->string('alias');
            $table->unsignedBigInteger('worker_id')->default(0);
            $table->timestamps();
            $table->unique(['alias']);
            $table->primary(['user_id','worker_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('calendar_aliases');
    }
}
