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
        Schema::create('entries', function (Blueprint $table) {
            $table->id();
            $table->integer('draw');
            $table->string('name');
            $table->integer('event_id');
            $table->integer('event_number');
            $table->date('event_date');
            $table->time('event_time');
            $table->dateTime('finish_time');
            $table->time('local_eventTimetostart');
            $table->time('local_eventTimetofinish');
            $table->string('event_type');
            $table->string('event_status');
            $table->integer('player_id');
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
        Schema::dropIfExists('entries');
    }
};
