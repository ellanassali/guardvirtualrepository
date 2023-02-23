<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBetselectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('betselections', function (Blueprint $table) {
            $table->id();
            $table->date('event_date');
            $table->time('event_time');
            $table->date('date_placed');
            $table->time('time_placed');
            $table->integer('event_id');
            $table->integer('event_no');
            $table->integer('draw');
            $table->string('market');
            $table->integer('stake');
            $table->decimal('selected_odd');
            $table->integer('payout');
            $table->string('barcode');
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
        Schema::dropIfExists('betselections');
    }
}
