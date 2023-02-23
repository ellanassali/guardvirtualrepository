<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBetssummariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('betssummaries', function (Blueprint $table) {
            $table->id();
            $table->integer('total_stake');
            $table->decimal('total_odd');
            $table->decimal('total_payout');
            $table->string('receiptid');
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
        Schema::dropIfExists('betssummaries');
    }
}
