<?php

namespace Tests\Unit;

use App\Audiogram;
use App\Patient;
use App\StandardThresholdShiftDetermination;
use Illuminate\Support\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StandardThresholdShiftDeterminationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function detect_standard_threshold_shifts()
    {
        $baselineAudiogram = factory(Audiogram::class)->state('normal')->create();
        $currentAudiogram = factory(Audiogram::class)->state('moderate-loss')->create();

        $stsd = new StandardThresholdShiftDetermination;

        $this->assertTrue($stsd->test($baselineAudiogram, $currentAudiogram));
    }

    /** @test */
    public function detect_no_standard_threshold_shift_in_identical_audiograms()
    {
        $baselineAudiogram = factory(Audiogram::class)->state('normal')->create();
        $currentAudiogram  = factory(Audiogram::class)->state('normal')->create();

        $stsd = new StandardThresholdShiftDetermination;

        $this->assertFalse($stsd->test($baselineAudiogram, $currentAudiogram));
    }

    /** @test */
    public function detect_no_standard_threshold_shift_with_minor_changes()
    {
        $baselineAudiogram = factory(Audiogram::class)->state('borderline-normal')->create();
        $currentAudiogram  = factory(Audiogram::class)->state('mild-loss')->create();

        $stsd = new StandardThresholdShiftDetermination;

        $this->assertFalse($stsd->test($baselineAudiogram, $currentAudiogram));
    }

    /** @test */
    public function use_age_related_adjustments_when_a_patient_is_provided()
    {
        $patient = factory(Patient::class)->create([
            'gender'    => 'male',
            'birthdate' => Carbon::now()->subYears(40)->subDays(7),
        ]);

        $baselineAudiogram = factory(Audiogram::class)->state('mild-loss')->create([
            'patient_id' => $patient->id,
            'date' => Carbon::now()->subYears(20),
        ]);
        $currentAudiogram  = factory(Audiogram::class)->state('moderate-loss')->create([
            'patient_id' => $patient->id,
            'date' => Carbon::today()
        ]);

        $stsd = new StandardThresholdShiftDetermination;

        $this->assertFalse($stsd->test($baselineAudiogram, $currentAudiogram, StandardThresholdShiftDetermination::USE_AGE_ADJUSTMENT));
    }
}
