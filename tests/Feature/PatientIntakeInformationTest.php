<?php

namespace Tests\Feature;

use App\IntakeForm;
use App\Patient;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PatientIntakeInformationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function record_patient_intake_medical_information()
    {
        Carbon::setTestNow(Carbon::parse('8/24/2018'));
        $patient = factory(Patient::class)->create();

        $response = $this->post(route('intake.store', $patient->id), $this->validData());

        $response->assertStatus(201);

        $intake = IntakeForm::find(1);

        $this->assertEquals('good', $intake->health);
        $this->assertTrue($intake->allergies);
        $this->assertFalse($intake->diabetes);
        $this->assertFalse($intake->dizziness);
        $this->assertTrue($intake->head_injury);
        $this->assertTrue($intake->hypertension);
        $this->assertFalse($intake->kidney_disease);
        $this->assertFalse($intake->measles);
        $this->assertFalse($intake->mumps);
        $this->assertFalse($intake->scarlet_fever);
        $this->assertFalse($intake->otorrhea);
        $this->assertFalse($intake->otalgia);
        $this->assertFalse($intake->ear_surgery);
        $this->assertFalse($intake->ear_medications);
        $this->assertTrue($intake->tinnitus);
        $this->assertFalse($intake->aural_pressure);
        $this->assertFalse($intake->perforated_tympanic_membrane);
        $this->assertTrue($intake->cerumen);
        $this->assertFalse($intake->ent_consult);
        $this->assertEquals('both', $intake->hearing_loss);
        $this->assertEquals('father', $intake->family_history_hearing_loss);
        $this->assertFalse($intake->use_amplification);
        $this->assertTrue($intake->previously_work_noise_exposure);
        $this->assertFalse($intake->audiology_consult);
        $this->assertFalse($intake->noise_exposure_recreational_gun_use);
        $this->assertFalse($intake->noise_exposure_power_tools);
        $this->assertFalse($intake->noise_exposure_engines);
        $this->assertTrue($intake->noise_exposure_loud_music);
        $this->assertFalse($intake->noise_exposure_farm_machinery);
        $this->assertFalse($intake->noise_exposure_military);
        $this->assertFalse($intake->noise_exposure_other);
    }

    protected function validData($overrides = [])
    {
        return array_merge([
            'hearing'                             => 'good',
            'health'                              => 'good',
            'allergies'                           => 1,
            'diabetes'                            => 0,
            'dizziness'                           => 0,
            'head_injury'                         => 1,
            'hypertension'                        => 1,
            'kidney_disease'                      => 0,
            'measles'                             => 0,
            'mumps'                               => 0,
            'scarlet_fever'                       => 0,
            'otorrhea'                            => 0,
            'otalgia'                             => 0,
            'ear_surgery'                         => 0,
            'ear_medications'                     => 0,
            'tinnitus'                            => 1,
            'aural_pressure'                      => 0,
            'perforated_tympanic_membrane'        => 0,
            'cerumen'                             => 1,
            'ent_consult'                         => 0,
            'hearing_loss'                        => 'both',
            'family_history_hearing_loss'         => 'father',
            'use_amplification'                   => 0,
            'previously_work_noise_exposure'      => 1,
            'audiology_consult'                   => 0,
            'noise_exposure_recreational_gun_use' => 0,
            'noise_exposure_power_tools'          => 0,
            'noise_exposure_engines'              => 0,
            'noise_exposure_loud_music'           => 1,
            'noise_exposure_farm_machinery'       => 0,
            'noise_exposure_military'             => 0,
            'noise_exposure_other'                => 0,
        ], $overrides);
    }
}
