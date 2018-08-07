<?php

namespace Tests\Unit;

use App\Audiogram;
use App\StandardThresholdShiftDetermination;
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
}
