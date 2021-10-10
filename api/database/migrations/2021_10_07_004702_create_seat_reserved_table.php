<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeatReservedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seat_reserved', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('seat_id');
            $table->unsignedBigInteger('reservation_id');
            $table->unsignedBigInteger('show_time_id');
            $table->timestamps();

            $table->foreign('seat_id')->references('id')->on('seats');
            $table->foreign('reservation_id')->references('id')->on('reservations');
            $table->foreign('show_time_id')->references('id')->on('show_times');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('seat_reserved');
    }
}
