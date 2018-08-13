<?php

namespace Tests\Unit;

use App\Patient;
use App\Response;
use App\Audiogram;
use Tests\TestCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AudiogramTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function create_a_new_audiogram_with_responses()
    {
        $patient = factory(Patient::class)->create();
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
}
