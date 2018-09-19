<?php

namespace Tests\Feature\Encounters;

use App\Encounters\Encounter;
use App\Users\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DepartEncounterTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function depart_an_encounter()
    {
    	$encounter = factory(Encounter::class)->state('arrived')->create();
    	$this->assertFalse($encounter->checkStatus('departed'));

    	$this->actingAs(new User)
            ->json('POST', route('encounters.depart.store', $encounter), []);

    	$this->assertTrue($encounter->fresh()->checkStatus('departed'));
    }

    /** @test */
    public function unauthenticated_users_cannot_depart_encounters()
    {
        $this->withExceptionHandling();

        $encounter = factory(Encounter::class)->state('arrived')->create();

        $response = $this->json('POST', route('encounters.depart.store', $encounter), []);
        $response->assertStatus(401);

        $this->assertFalse($encounter->fresh()->checkStatus('departed'));
    }

    /** @test */
    public function cannot_depart_an_encounter_that_has_not_previously_arrived()
    {
        $this->withExceptionHandling();

        $encounter = factory(Encounter::class)->state('today')->create();

        $response = $this->actingAs(new User)
            ->json('POST', route('encounters.depart.store', $encounter), []);

        $response->assertValidationError('status');
        $this->assertEquals('Encounter has not yet been arrived.', $response->decodeResponseJson('errors.status.0'));
    }

    /** @test */
    public function cannot_depart_an_encounter_that_has_been_departed()
    {
        $this->withExceptionHandling();

        $encounter = factory(Encounter::class)->state('departed')->create();

        $response = $this->actingAs(new User)
            ->json('POST', route('encounters.depart.store', $encounter), []);

        $response->assertValidationError('status');
        $this->assertEquals('Cannot depart an encounter that has already been departed', $response->decodeResponseJson('errors.status.0'));
    }

    /** @test */
    public function cannot_depart_an_encounter_that_has_been_cancelled()
    {
        $this->withExceptionHandling();

        $encounter = factory(Encounter::class)->state('cancelled')->create();

        $response = $this->actingAs(new User)
            ->json('POST', route('encounters.depart.store', $encounter), []);

        $response->assertValidationError('status');
        $this->assertEquals('Cannot depart an encounter from cancelled status', $response->decodeResponseJson('errors.status.0'));
    }

    /** @test */
    public function cannot_depart_an_encounter_that_has_been_rescheduled()
    {
        $this->withExceptionHandling();

        $encounter = factory(Encounter::class)->state('rescheduled')->create();

        $response = $this->actingAs(new User)
            ->json('POST', route('encounters.depart.store', $encounter), []);

        $response->assertValidationError('status');
        $this->assertEquals('Cannot depart an encounter from rescheduled status', $response->decodeResponseJson('errors.status.0'));
    }
}
