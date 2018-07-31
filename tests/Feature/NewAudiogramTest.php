<?php

namespace Tests\Feature;

use App\Audiogram;
use App\Patient;
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
}
