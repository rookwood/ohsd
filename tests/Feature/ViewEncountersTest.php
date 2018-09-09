<?php

namespace Tests\Feature;

use App\Encounters\Encounter;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ViewEncountersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function view_todays_appointments()
    {
    	$encounters = factory(Encounter::class, 3)->state('today')->create();
    	$oldEncounters = factory(Encounter::class, 1)->state('old')->create();

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
        $this->assertEquals('scheduled', $responseEncounters[0]['status']);
    }

    /** @test */
    public function view_this_weeks_appointments()
    {
        $todayEncounters    = factory(Encounter::class, 2)->state('today')->create();
        $tomorrowEncounters = factory(Encounter::class, 2)->state('tomorrow')->create();
        $oldEncounters = factory(Encounter::class, 1)->state('old')->create();

        $response = $this->json('GET', route('encounters.week.index'));

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

        $this->assertCount(4, $response->decodeResponseJson()['data']);
    }
}
