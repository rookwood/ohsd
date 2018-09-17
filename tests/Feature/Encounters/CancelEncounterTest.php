<?php

namespace Tests\Feature\Encounters;

use App\Encounters\Encounter;
use App\Encounters\EncounterStatus;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CancelEncounterTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function cancel_an_upcoming_encounter()
    {
    	$encounter = factory(Encounter::class)->state('tomorrow')->create();

    	$reason = 'Patient is violently afraid of cloudy weather.';
    	$this->json('POST', route('encounters.cancel.store', $encounter), [
    	    'reason' => $reason
        ]);

    	$this->assertEquals('cancelled', EncounterStatus::guess($encounter->fresh()));
    	$this->assertEquals($reason, $encounter->fresh()->cancellation_reason);
    }
}
