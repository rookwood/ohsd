<?php

namespace Tests\Feature;

use App\Encounter;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ViewEncountersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function view_todays_appointments()
    {
    	$encounters = factory(Encounter::class, 3)->state('today')->create();

    	$response = $this->json('GET', route('encounters.today.index'));

    	$response->assertOk();
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'start_at',
                    'patient',
                    'status',
                    'notes',
                ]
            ]
        ]);

        $responseEncounters = $response->decodeResponseJson()['data'];
        $this->assertCount(3, $responseEncounters);
        $this->assertEquals($encounters->first()->start_at, $responseEncounters[0]['start_at']);
        $this->assertEquals($encounters->first()->patient_id, $responseEncounters[0]['patient']['id']);
        $this->assertEquals($encounters->first()->notes, $responseEncounters[0]['notes']);
        // Requires more implementation work
        // $this->assertEquals('scheduled', $responseEncounters[0]->status);
    }

    /** @test */
    // public function view_this_weeks_appointments()
    // {
    // 	//
    // }
}
