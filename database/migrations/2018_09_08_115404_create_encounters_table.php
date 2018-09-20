<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEncountersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('encounters', function (Blueprint $table) {
            $table->increments('id');
            $table->dateTime('start_at');
            $table->unsignedInteger('patient_id');
            $table->text('notes')->nullable();
            $table->dateTime('scheduled_at')->default('');
            $table->dateTime('arrived_at')->nullable();
            $table->dateTime('departed_at')->nullable();
            $table->dateTime('cancelled_at')->nullable();
            $table->string('cancellation_reason')->nullable();
            $table->dateTime('rescheduled_at')->nullable();
            $table->unsignedInteger('rescheduled_from')->nullable();
            $table->unsignedInteger('rescheduled_to')->nullable();
            $table->string('rescheduled_reason')->nullable();
            $table->unsignedInteger('scheduled_by');
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
        Schema::dropIfExists('encounters');
    }
}
