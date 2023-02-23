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
        Schema::create('racewinresults', function (Blueprint $table) {
            $table->id();
            $table->integer('event_id');
            $table->integer('event_no');
            $table->date('event_date');
            $table->time('event_time');
            $table->dateTime('event_finishTime');
            $table->time('local_eventTimetostart');
            $table->time('local_eventTimetofinish');
            $table->string('event_type');
            $table->string('entry_id')->nullable();
            $table->string('selection_id');
            $table->string('entry_name')->nullable();
            $table->decimal('odd');
            $table->integer('win_status');
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
        Schema::dropIfExists('racewinresults');
    }
};
