<?php

namespace Tests\Unit;

use App\Encounters\Encounter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EncounterTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function rescheduling_relationships()
    {
        $encounterA = factory(Encounter::class)->create();
        $encounterB = factory(Encounter::class)->create();
        $encounterC = factory(Encounter::class)->create([
            'rescheduled_from' => $encounterA->id,
            'rescheduled_to' => $encounterB->id,
        ]);

        $this->assertTrue($encounterC->rescheduledFromEncounter->is($encounterA));
        $this->assertTrue($encounterC->rescheduledToEncounter->is($encounterB));
    }
}
