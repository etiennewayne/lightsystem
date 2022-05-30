<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id('schedule_id');
            $table->unsignedBigInteger('device_id');
            $table->foreign('device_id')->references('device_id')->on('devices');
            $table->string('schedule_name')->nullable();
            $table->dateTime('date_time')->nullable();
            $table->string('system_action')->nullable();
            $table->string('action_type')->nullable();
            $table->string('ntuser')->nullable();
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
        Schema::dropIfExists('schedules');
    }
}
