<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('raceresults', function (Blueprint $table) {
            $table->id();
            $table->integer('event_id');
            $table->integer('event_no');
            $table->date('event_date');
            $table->time('event_time');
            $table->dateTime('event_finishTime');
            $table->time('local_eventTimetostart');
            $table->time('local_eventTimetofinish');
            $table->string('event_type');
            $table->integer('playsPaysOn');
            $table->integer('entry_id');
            $table->string('entry_name');
            $table->integer('finish_position')->nullable();
            $table->integer('place_position');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('raceresults');
    }
};
