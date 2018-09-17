<?php

namespace Tests\Feature\Encounters;

use App\Encounters\Encounter;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ArriveEncounterTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function mark_encounter_as_arrived()
    {
    	$encounter = factory(Encounter::class)->state('today')->create();
    	$this->assertFalse($encounter->checkStatus('arrived'));

    	$this->post(route('encounters.arrival.store', $encounter), []);

    	$this->assertTrue($encounter->fresh()->checkStatus('arrived'));
    }

    /** @test */
    public function encounters_cannot_be_arrived_if_already_marked_as_arrived()
    {
        $this->withExceptionHandling();

        $encounter = factory(Encounter::class)->state('arrived')->create();

        $response = $this->json('POST', route('encounters.arrival.store', $encounter), []);

        $response->assertValidationError('status');
        $this->assertEquals('Encounter already marked as arrived', $response->decodeResponseJson('errors.status.0'));
    }

    /** @test */
    public function encounters_cannot_be_arrived_if_cancelled()
    {
        $this->withExceptionHandling();

        $encounter = factory(Encounter::class)->state('cancelled')->create();

        $response = $this->json('POST', route('encounters.arrival.store', $encounter), []);

        $response->assertValidationError('status');
        $this->assertEquals('Encounter already marked as cancelled', $response->decodeResponseJson('errors.status.0'));
    }

    /** @test */
    public function encounters_cannot_be_arrived_if_rescheduled()
    {
        $this->withExceptionHandling();

        $encounter = factory(Encounter::class)->state('rescheduled')->create();

        $response = $this->json('POST', route('encounters.arrival.store', $encounter), []);

        $response->assertValidationError('status');
        $this->assertEquals('Encounter already marked as rescheduled', $response->decodeResponseJson('errors.status.0'));
    }
}
