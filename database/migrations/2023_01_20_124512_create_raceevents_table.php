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
        Schema::create('raceevents', function (Blueprint $table) {
            $table->id();
            $table->string('eventType');
            $table->integer('eventNumber');
            $table->date('event_date');
            $table->time('event_time');
            $table->time('local_eventTimetostart');
            $table->time('local_eventTimetofinish');
            $table->dateTime('finishTime');
            $table->string('eventStatus');
            $table->integer('distance');
            $table->string('name');
            $table->integer('playsPaysOn');
            $table->dateTime('localTime');
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
        Schema::dropIfExists('raceevents');
    }
};
