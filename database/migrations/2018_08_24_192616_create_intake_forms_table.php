<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIntakeFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('intake_forms', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('patient_id');
            $table->date('date');
            $table->string('hearing')->nullable();
            $table->string('health')->nullable();
            $table->boolean('allergies')->nullable();
            $table->boolean('diabetes')->nullable();
            $table->boolean('dizziness')->nullable();
            $table->boolean('head_injury')->nullable();
            $table->boolean('hypertension')->nullable();
            $table->boolean('kidney_disease')->nullable();
            $table->boolean('measles')->nullable();
            $table->boolean('mumps')->nullable();
            $table->boolean('scarlet_fever')->nullable();
            $table->boolean('otorrhea')->nullable();
            $table->boolean('otalgia')->nullable();
            $table->boolean('ear_surgery')->nullable();
            $table->boolean('ear_medications')->nullable();
            $table->boolean('tinnitus')->nullable();
            $table->boolean('aural_pressure')->nullable();
            $table->boolean('perforated_tympanic_membrane')->nullable();
            $table->boolean('cerumen')->nullable();
            $table->boolean('ent_consult')->nullable();
            $table->string('hearing_loss')->nullable();
            $table->string('family_history_hearing_loss')->nullable();
            $table->boolean('use_amplification')->nullable();
            $table->boolean('previously_work_noise_exposure')->nullable();
            $table->boolean('audiology_consult')->nullable();
            $table->boolean('noise_exposure_recreational_gun_use')->nullable();
            $table->boolean('noise_exposure_power_tools')->nullable();
            $table->boolean('noise_exposure_engines')->nullable();
            $table->boolean('noise_exposure_loud_music')->nullable();
            $table->boolean('noise_exposure_farm_machinery')->nullable();
            $table->boolean('noise_exposure_military')->nullable();
            $table->boolean('noise_exposure_other')->nullable();
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
        Schema::dropIfExists('intake_forms');
    }
}
