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
        $responsae = $this->actingAs(factory(User::class)->create())
            ->json('POST', route('encounters.reschedule.store', $encounter), [
                'date' => $newEncounterTime->format('Y-m-d'),
                'time' => $newEncounterTime->format('H:i a'),
                'reason' => 'Because reasons'
            ]);

        $this->assertTrue($encounter->fresh()->checkStatus('rescheduled'));
        $this->assertEquals(2, $encounter->fresh()->rescheduled_to);
        $this->assertDatabaseHas('encounters', [
            'patient_id' => $encounter->patient_id,
            'start_at' => $newEncounterTime->format('Y-m-d H:i:00'),
            'rescheduled_from' => 1
        ]);

        $this->fail('Need to deal with rescheduling relations for previous / next appts and all of the fallout from those changes in other tests');
        $responsae->assertStatus(201);

        $responsae->assertJsonStructure([
            'data' => [
                'start_at',
                'patient',
                'notes',
                'status',
                'rescheduled_from'
            ]
        ]);
    }
}
