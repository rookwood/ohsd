<?php

namespace Tests\Feature;

use App\Employer;
use App\Encounters\Encounter;
use App\IntakeForm;
use App\Response;
use App\Users\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EvaluateNewPatientTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function evaluate_hearing_at_a_patients_first_visit()
    {
        $audiologist = factory(User::class)->create();
        $employer = factory(Employer::class)->create();
        $encounterTime = Carbon::now();
        $patient = $this->patientData();

        $newPatientResponse = $this->actingAs($audiologist)
            ->json('POST', route('patients.store'), array_merge($patient, ['employer_id' => $employer->id]))
            ->decodeResponseJson();

        $newEncounterResponse = $this->actingAs($audiologist)
            ->json('POST', route('encounters.store', $newPatientResponse['data']['id']), [
                'date' => $encounterTime->format('Y-m-d'),
                'time' => $encounterTime->format('H:i'),
                'notes' => 'New employee, post-hire baseline evaluation'
            ])->decodeResponseJson();

        $this->actingAs($audiologist)
            ->json('POST', route('encounters.arrival.store', $newEncounterResponse['data']['id']));

        $intakeResponse = $this->actingAs($audiologist)
            ->json('POST', route('intake.store', $newPatientResponse['data']['id']), factory(IntakeForm::class)->make()->toArray());

        $audiogramResponse = $this->actingAs($audiologist)
            ->json('POST', route('audiograms.store', $newPatientResponse['data']['id']), $this->evaluationResults($encounterTime))
            ->decodeResponseJson();

        $this->actingAs($audiologist)
            ->json('POST', route('encounters.depart.store', $newEncounterResponse['data']['id']), []);

        $this->actingAs($audiologist)
            ->json('POST', route('encounters.finalize.store', $newEncounterResponse['data']['id']), [
                'outcome' => 'completed',
                'notes' => 'New hire eval completed, normal hearing',
                'audiogram_id' => $audiogramResponse['data']['id'],
            ]);

        $this->assertDatabaseHas('encounters', [
            'id' => $newPatientResponse['data']['id'],
            'patient_id' => $newPatientResponse['data']['id'],
            'outcome' => 'completed'
        ]);

        $this->assertCount(10, Response::all());
        $this->assertNotNull(Encounter::first()->audiogram);
    }

    protected function patientData()
    {
        return [
            'firstname' => $this->faker->firstName('male'),
            'lastname' => $this->faker->lastName,
            'birthdate' => '1995-10-13',
            'gender' => 'male',
            'hire_date' => '2018-09-18',
            'title' => 'grunt',
            'employee_id' => 18090001
        ];
    }

    protected function evaluationResults($date)
    {
        return [
            'noise_exposure' => 'no',
            'hearing_protection' => 'n/a',
            'otoscopy' => 'pass',
            'date' => $date->format('Y-m-d'),
            'responses' => array_merge(
                array_map(function($frequency) {
                    return $this->normalResponse($frequency, 'right');
                }, $this->screeningFrequencies()),
                array_map(function ($frequency) {
                    return $this->normalResponse($frequency, 'left');
                }, $this->screeningFrequencies())
            ),
        ];
    }

    protected function screeningFrequencies()
    {
        return ['500', '1000', '2000', '3000', '4000'];
    }

    protected function normalResponse($frequency, $ear)
    {
        return [
            'frequency' => $frequency,
            'ear' => $ear,
            'amplitude' => 15,
            'stimulus' => 'tone',
            'test' => 'threshold',
            'masking' => false,
            'modality' => 'air',
        ];
    }
}
