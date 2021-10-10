<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('show_time_id');
            $table->unsignedInteger('num_of_seats');
            $table->unsignedDecimal('total_cost');
            $table->boolean('status')->default(true);
            $table->timestamps();

            $table->foreign('employee_id')->references('id')->on('users');
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
        Schema::dropIfExists('reservations');
    }
}
