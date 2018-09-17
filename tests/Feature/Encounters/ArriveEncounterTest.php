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
}
