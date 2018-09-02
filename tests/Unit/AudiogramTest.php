<?php

namespace Tests\Unit;

use App\Events\TestResultWasLogged;
use App\Patient;
use App\Response;
use App\Audiogram;
use Carbon\Carbon;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AudiogramTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function create_a_new_audiogram_with_responses()
    {
        Event::fake();

        $patient = factory(Patient::class)->make(['id' => 1]);
        $responses = factory(Response::class, 3)->make();
        $testData = factory(Audiogram::class)->make(['patient_id' => $patient->id]);

        Auth::shouldReceive('user')->andReturn((object) ['id' => 1]);

        $audiogram = Audiogram::newScreeningForPatient($patient, $testData->toArray(), $responses->toArray());

        $this->assertCount(1, Audiogram::all());
        $this->assertDatabaseHas('audiograms', $testData->toArray());

        $this->assertCount(3, $audiogram->responses);

        $responses->each(function ($response) {
            $this->assertDatabaseHas('responses', $response->toArray());
        });
    }

    /** @test */
    public function initial_audiogram_is_baseline_by_default()
    {
    	$audiogramA = factory(Audiogram::class)->create(['patient_id' => 1]);
    	$audiogramB = factory(Audiogram::class)->create(['patient_id' => 1]);

    	$this->assertTrue($audiogramA->fresh()->isBaseline());
    	$this->assertFalse($audiogramB->fresh()->isBaseline());
    }

    /** @test */
    public function get_relative_baseline()
    {
        $audiogramA = factory(Audiogram::class)
            ->state('normal')
            ->create(['patient_id' => 1]);

        $audiogramB = factory(Audiogram::class)
            ->state('normal')
            ->create(['patient_id' => 1]);

        $audiogramC = factory(Audiogram::class)
            ->state('normal')
            ->create(['patient_id' => 1]);

        $this->assertTrue($audiogramC->getBaseline()->is($audiogramA));
    }

    /** @test */
    public function audiogram_with_new_threshold_shift_becomes_baseline()
    {
        $patient = factory(Patient::class)->create();
        $audiogramA = factory(Audiogram::class)
            ->state('normal')
            ->create(['patient_id' => $patient->id]);

        $audiogramB = factory(Audiogram::class)
            ->state('moderate-loss')
            ->create(['patient_id' => $patient->id]);

        // Simulate saving through actual static constructor
        event(new TestResultWasLogged($audiogramB));

        $this->assertTrue($audiogramB->fresh()->isBaseline());
    }

    /** @test */
    public function audiogram_showing_threshold_shift_still_gets_previous_record_as_baseline()
    {
        $patient = factory(Patient::class)->create();
        $original = factory(Audiogram::class)
            ->state('normal')
            ->create([
                'patient_id' => $patient->id,
                'created_at' => Carbon::now()->subDays(2),
            ]);

        $firstLossDetected = factory(Audiogram::class)
            ->state('moderate-loss')->create([
                'patient_id' => $patient->id,
                'created_at' => Carbon::now()->subDays(1),
            ]);

        // Simulate saving through actual static constructor
        event(new TestResultWasLogged($firstLossDetected));

        $followUpTest = factory(Audiogram::class)
            ->state('moderate-loss')->create([
                'patient_id' => $patient->id,
                'created_at' => Carbon::now(),
            ]);

        $this->assertTrue($followUpTest->getBaseline()->is($firstLossDetected));
        $this->assertTrue($firstLossDetected->getBaseline()->is($original));
    }
}
