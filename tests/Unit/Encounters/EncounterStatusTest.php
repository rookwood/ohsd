<?php

namespace Tests\Unit\Encounters;

use App\Encounters\Encounter;
use App\Encounters\EncounterStatus;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EncounterStatusTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function encounters_are_scheduled_when_patient_has_not_arrived_or_cancelled()
    {
    	$scheduledEncounter = factory(Encounter::class)->create([
    	    'start_at' => $this->faker->dateTime,
            'arrived_at' => null,
            'cancelled_at' => null,
        ]);

    	$arrivedEncounter = factory(Encounter::class)->create([
            'start_at'     => $this->faker->dateTime,
            'arrived_at'   => $this->faker->dateTime,
            'cancelled_at' => null,
        ]);

    	$cancelledEncounter = factory(Encounter::class)->create([
            'start_at'     => $this->faker->dateTime,
            'arrived_at'   => null,
            'cancelled_at' => $this->faker->dateTime,
        ]);

    	$retrievedEncounters = Encounter::status(new EncounterStatus('scheduled'))->get();
    	$this->assertCount(1, $retrievedEncounters);
    	$this->assertTrue($retrievedEncounters->first()->is($scheduledEncounter));
    }

    /** @test */
    public function encounters_are_arrived_when_patient_arrived_but_not_departed()
    {
    	$scheduledEncounter = factory(Encounter::class)->create([
    	    'start_at' => $this->faker->dateTime,
            'arrived_at' => null,
            'cancelled_at' => null,
        ]);

    	$arrivedEncounter = factory(Encounter::class)->create([
            'start_at'     => $this->faker->dateTime,
            'arrived_at'   => $this->faker->dateTime,
            'cancelled_at' => null,
        ]);

    	$departedEncounter = factory(Encounter::class)->create([
            'start_at'     => $this->faker->dateTime,
            'arrived_at'   => $this->faker->dateTime,
            'departed_at' => $this->faker->dateTime,
        ]);

    	$retrievedEncounters = Encounter::status(new EncounterStatus('arrived'))->get();
    	$this->assertCount(1, $retrievedEncounters);
    	$this->assertTrue($retrievedEncounters->first()->is($arrivedEncounter));
    }

    /** @test */
    public function encounters_are_departed_when_patient_arrived_and_then_departed()
    {
    	$scheduledEncounter = factory(Encounter::class)->create([
    	    'start_at' => $this->faker->dateTime,
            'arrived_at' => null,
            'cancelled_at' => null,
        ]);

    	$arrivedEncounter = factory(Encounter::class)->create([
            'start_at'     => $this->faker->dateTime,
            'arrived_at'   => $this->faker->dateTime,
            'cancelled_at' => null,
        ]);

    	$departedEncounter = factory(Encounter::class)->create([
            'start_at'     => $this->faker->dateTime,
            'arrived_at'   => $this->faker->dateTime,
            'departed_at' => $this->faker->dateTime,
        ]);

    	$retrievedEncounters = Encounter::status(new EncounterStatus('departed'))->get();
    	$this->assertCount(1, $retrievedEncounters);
    	$this->assertTrue($retrievedEncounters->first()->is($departedEncounter));
    }

    /** @test */
    public function encounters_are_cancelled_when_patient_has_cancelled_but_not_rescheduled()
    {
    	$scheduledEncounter = factory(Encounter::class)->create([
    	    'start_at' => $this->faker->dateTime,
            'arrived_at' => null,
            'cancelled_at' => null,
        ]);

    	$rescheduledEncounter = factory(Encounter::class)->create([
            'start_at'     => $this->faker->dateTime,
            'arrived_at'   => $this->faker->dateTime,
            'cancelled_at' => $this->faker->dateTime,
            'rescheduled_to' => $this->faker->dateTime,
        ]);

        $cancelledEncounter = factory(Encounter::class)->create([
            'start_at'     => $this->faker->dateTime,
            'arrived_at'   => null,
            'cancelled_at' => $this->faker->dateTime,
            'rescheduled_to' => null,
        ]);

        $retrievedEncounters = Encounter::status(new EncounterStatus('cancelled'))->get();
    	$this->assertCount(1, $retrievedEncounters);
    	$this->assertTrue($retrievedEncounters->first()->is($cancelledEncounter));
    }

    /** @test */
    public function encounters_are_rescheduled_when_patient_has_cancelled_and_rescheduled()
    {
        $scheduledEncounter = factory(Encounter::class)->create([
            'start_at'     => $this->faker->dateTime,
            'arrived_at'   => null,
            'cancelled_at' => null,
        ]);

        $rescheduledEncounter = factory(Encounter::class)->create([
            'start_at'       => $this->faker->dateTime,
            'arrived_at'     => $this->faker->dateTime,
            'cancelled_at'   => null,
            'rescheduled_to' => $this->faker->dateTime,
        ]);

        $cancelledEncounter = factory(Encounter::class)->create([
            'start_at'       => $this->faker->dateTime,
            'arrived_at'     => null,
            'cancelled_at'   => $this->faker->dateTime,
            'rescheduled_to' => null,
        ]);

        $retrievedEncounters = Encounter::status(new EncounterStatus('rescheduled'))->get();
        $this->assertCount(1, $retrievedEncounters);
        $this->assertTrue($retrievedEncounters->first()->is($rescheduledEncounter));
    }

}
