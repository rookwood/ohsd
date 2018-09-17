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

    /** @test */
    public function encounters_cannot_be_cancelled_if_already_cancelled()
    {
        $this->withExceptionHandling();

    	$encounter = factory(Encounter::class)->state('cancelled')->create();

    	$response = $this->json('POST', route('encounters.cancel.store', $encounter), []);

        $response->assertValidationError('status');
        $this->assertEquals('Encounter already marked as cancelled', $response->decodeResponseJson('errors.status.0'));
    }

    /** @test */
    public function encounters_cannot_be_cancelled_if_already_rescheduled()
    {
    	$this->withExceptionHandling();

    	$encounter = factory(Encounter::class)->state('rescheduled')->create();

    	$response = $this->json('POST', route('encounters.cancel.store', $encounter), []);

    	$response->assertValidationError('status');
    	$this->assertEquals('Encounter already marked as rescheduled', $response->decodeResponseJson('errors.status.0'));
    }

    /** @test */
    public function encounters_cannot_be_cancelled_once_departed()
    {
        $this->withExceptionHandling();

        $encounter = factory(Encounter::class)->state('departed')->create();

        $response = $this->json('POST', route('encounters.cancel.store', $encounter), []);

        $response->assertValidationError('status');
        $this->assertEquals('Encounter already marked as departed', $response->decodeResponseJson('errors.status.0'));
    }
}
