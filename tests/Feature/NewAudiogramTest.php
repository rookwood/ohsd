<?php

namespace Tests\Feature;

use App\Audiogram;
use App\Patient;
use App\Response;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NewAudiogramTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function viewing_a_patients_audiograms()
    {
        $audiologist = factory(User::class)->create();
        $patient = factory(Patient::class)->create();
        $audiograms = factory(Audiogram::class, 3)->create([
            'patient_id' => $patient->id,
            'user_id' => $audiologist->id
        ]);

        $response = $this->get(route('patients.show', $patient));

        $response->assertSuccessful();
        $response->assertViewIs('patients.show');

        $this->assertCount(3, $response->data('audiograms'));
        $audiograms->assertEquals($response->data('audiograms'));
    }

    /** @test */
    public function test_results_can_be_logged()
    {
        $user = factory(User::class)->create();
        $patient = factory(Patient::class)->create();

        $response = $this->actingAs($user)->post(route('audiograms.store', $patient), [
            'noise_exposure' => 'no',
            'hearing_protection' => 'yes',
            'otoscopy' => 'pass',
            'comment' => 'test comment',
            'date' => '2018-07-01',
            'responses' => [
                [
                    'frequency' => '500',
                    'ear' => 'right',
                    'amplitude' => '20',
                    'masking' => '0',
                    'modality' => 'air',
                    'no_response' => '0'
                ],
                [
                    'frequency' => '1000',
                    'ear' => 'left',
                    'amplitude' => '26',
                    'masking' => '1',
                    'modality' => 'bone',
                    'no_response' => '0'
                ],
            ]
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('patients.show', $patient));

        $this->assertCount(1, Audiogram::all());
        $this->assertCount(2, Response::all());

        $audiogram = Audiogram::first();

        $this->assertEquals($user->id, $audiogram->user_id);
        $this->assertEquals($patient->id, $audiogram->patient_id);
        $this->assertTrue($audiogram->passedOtoscopicEvaluation());
        $this->assertTrue($audiogram->avoidedNoiseExposurePriorToEvaluation());
        $this->assertTrue($audiogram->woreHearingProtectionSinceLastEvaluation());
        $this->assertEquals('test comment', $audiogram->comment);

        $response = Response::first();

        $this->assertEquals(500, $response->frequency);
        $this->assertEquals('right', $response->ear);
        $this->assertEquals(20, $response->amplitude);
        $this->assertFalse($response->masking);
        $this->assertEquals('air', $response->modality);
        $this->assertFalse($response->no_response);
    }
}
