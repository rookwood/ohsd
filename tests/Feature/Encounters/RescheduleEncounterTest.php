<?php

namespace Tests\Feature\Encounters;

use App\Encounters\Encounter;
use App\Users\User;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RescheduleEncounterTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function reschedule_an_existing_encounter()
    {
        $encounter = factory(Encounter::class)->state('tomorrow')->create();

        $newEncounterTime = Carbon::now()->addWeek();
        $response = $this->actingAs(factory(User::class)->create())
            ->json('POST', route('encounters.reschedule.store', $encounter), [
                'date' => $newEncounterTime->format('Y-m-d'),
                'time' => $newEncounterTime->format('H:i:00'),
                'reason' => 'Because reasons'
            ]);

        $response->assertStatus(201);

        $this->assertTrue($encounter->fresh()->checkStatus('rescheduled'));
        $this->assertNotNull($encounter->fresh()->rescheduled_at);
        $this->assertNotNull($encounter->fresh()->rescheduled_by);
        $this->assertEquals(2, $encounter->fresh()->rescheduledToEncounter->id);
        $this->assertDatabaseHas('encounters', [
            'patient_id' => $encounter->patient_id,
            'start_at' => $newEncounterTime->format('Y-m-d H:i:00'),
            'rescheduled_from' => 1
        ]);

        $response->assertJsonStructure([
            'data' => [
                'start_at',
                'patient',
                'notes',
                'status',
                'rescheduled_from'
            ]
        ]);
    }

    /** @test */
    public function unauthenticated_users_cannot_reschedule_appointments()
    {
        $this->withExceptionHandling();

        $encounter = factory(Encounter::class)->state('tomorrow')->create();

        $response = $this->json('POST', route('encounters.reschedule.store', $encounter), [
            'date' => '2100-01-01',
            'time' => '13:00:00',
            'reasons' => 'blah'
        ]);

        $response->assertStatus(401);
        $this->assertNull($encounter->fresh()->rescheduled_to);
    }

    /** @test */
    public function date_is_required()
    {
        $this->withExceptionHandling();

        $encounter = factory(Encounter::class)->state('tomorrow')->create();

        $response = $this->actingAs(factory(User::class)->state('audiologist')->create())
            ->json('POST', route('encounters.reschedule.store', $encounter), [
                'time'    => '13:00:00',
                'reasons' => 'blah'
        ]);

        $response->assertValidationError('date');
        $this->assertNull($encounter->fresh()->rescheduled_to);
    }

    /** @test */
    public function time_is_required()
    {
        $this->withExceptionHandling();

        $encounter = factory(Encounter::class)->state('tomorrow')->create();

        $response = $this->actingAs(factory(User::class)->state('audiologist')->create())
            ->json('POST', route('encounters.reschedule.store', $encounter), [
                'date' => '2100-01-01',
                'reasons' => 'blah'
        ]);

        $response->assertValidationError('time');
        $this->assertNull($encounter->fresh()->rescheduled_to);
    }

    /** @test */
    public function cannot_reschedule_a_departed_encounter()
    {
        $this->withExceptionHandling();

        $encounter = factory(Encounter::class)->state('departed')->create();

        $response = $this->actingAs(factory(User::class)->state('audiologist')->create())
            ->json('POST', route('encounters.reschedule.store', $encounter), [
                'date' => '2100-01-01',
                'time' => '13:00:00',
                'reasons' => 'blah'
        ]);

        $response->assertValidationError('status');
        $this->assertEquals('Cannot reschedule a departed encounter.', $response->decodeResponseJson('errors.status.0'));
        $this->assertNull($encounter->fresh()->rescheduled_to);
    }

    /** @test */
    public function cannot_reschedule_a_cancelled_encounter()
    {
        $this->withExceptionHandling();

        $encounter = factory(Encounter::class)->state('cancelled')->create();

        $response = $this->actingAs(factory(User::class)->state('audiologist')->create())
            ->json('POST', route('encounters.reschedule.store', $encounter), [
                'date' => '2100-01-01',
                'time' => '13:00:00',
                'reasons' => 'blah'
        ]);

        $response->assertValidationError('status');
        $this->assertEquals('Cannot reschedule a cancelled encounter.', $response->decodeResponseJson('errors.status.0'));
        $this->assertNull($encounter->fresh()->rescheduled_to);
    }

    /** @test */
    public function cannot_reschedule_an_already_rescheduled_encounter()
    {
        $this->withExceptionHandling();

        $encounter = factory(Encounter::class)->state('rescheduled')->create();

        $response = $this->actingAs(factory(User::class)->state('audiologist')->create())
            ->json('POST', route('encounters.reschedule.store', $encounter), [
                'date' => '2100-01-01',
                'time' => '13:00:00',
                'reasons' => 'blah'
        ]);

        $response->assertValidationError('status');
        $this->assertEquals('Cannot reschedule an encounter that has already been rescheduled.', $response->decodeResponseJson('errors.status.0'));
        $this->assertEquals($encounter->rescheduled_to, $encounter->fresh()->rescheduled_to);
    }
}
