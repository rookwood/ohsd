<?php

namespace Tests\Feature\Encounters;

use App\Audiogram;
use App\Encounters\Encounter;
use App\Users\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FinalizeEncounterTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function mark_encounter_as_completed()
    {
        $encounter = factory(Encounter::class)->state('departed')->create();
        $audiogram = factory(Audiogram::class)->state('normal')->create([
            'encounter_id' => $encounter->id
        ]);

        $response = $this->actingAs(factory(User::class)->state('audiologist')->create())
            ->json('POST', route('encounters.finalize.store', $encounter), [
                'outcome' => 'completed'
            ]);

        $this->assertTrue($encounter->fresh()->checkStatus('final'));
        $this->assertNotNull($encounter->fresh()->finalized_by);
        $this->assertNotNull($encounter->fresh()->finalized_at);
        $response->assertStatus(201);
    }
}
