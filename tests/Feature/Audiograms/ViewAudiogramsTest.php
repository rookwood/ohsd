<?php

namespace Tests\Feature\Audiograms;

use App\Users\User;
use App\Patient;
use App\Audiogram;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ViewAudiogramsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function viewing_a_patients_audiograms()
    {
        $audiologist = factory(User::class)->create();
        $patient     = factory(Patient::class)->create();
        $audiograms  = factory(Audiogram::class, 3)->create([
            'patient_id' => $patient->id,
            'user_id'    => $audiologist->id
        ]);
        $notThatPatientsAudiogram = factory(Audiogram::class)->create();

        $response = $this->actingAs($audiologist)
            ->json('GET', route('patients.show', $patient));

        $response->assertSuccessful();

        $this->assertCount(3, $response->decodeResponseJson('data.audiograms'));
    }

    /** @test */
    public function audiograms_entry_form_cannot_be_viewed_by_unauthenticated_users()
    {
    	$this->withExceptionHandling();

        $response = $this->get(route('audiograms.create', 1));

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }
}
