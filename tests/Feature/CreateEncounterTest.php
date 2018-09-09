<?php

namespace Tests\Feature;

use App\Encounters\Encounter;
use App\Patient;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateEncounterTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function schedule_future_encounter_for_patient()
    {
    	Auth::shouldReceive('user')->andReturn((object) ['id' => 1]);

        $patient = factory(Patient::class)->create();

    	$response = $this->json('POST', route('encounters.store', $patient), [
    	    'date' => '2018-09-09',
            'time' => '10:00 am',
            'notes' => 'Test encounter note',
        ]);

    	$response->assertStatus(201);

    	$response->assertJsonStructure([
    	    'data' => [
    	        'start_at',
                'patient',
                'status',
                'notes',
            ]
        ]);

    	$this->assertCount(1, Encounter::all());
    }
}
