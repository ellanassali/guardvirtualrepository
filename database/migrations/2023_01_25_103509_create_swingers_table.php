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
        Schema::create('swingers', function (Blueprint $table) {
            $table->id();
            $table->decimal('odd');
            $table->string('name');
            $table->integer('event_id');
            $table->integer('event_no');
            $table->date('event_date');
            $table->time('event_time');
            $table->dateTime('finish_time');
            $table->time('local_eventTimetostart');
            $table->time('local_eventTimetofinish');
            $table->string('event_type');
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
        Schema::dropIfExists('swingers');
    }
};
