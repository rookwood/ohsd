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
        $currentAudiogram = factory(Audiogram::class)->state('moderate-hearing-loss')->create();

    	$stsd = new StandardThresholdShiftDetermination;

    	$this->assertTrue($stsd->test($baselineAudiogram, $currentAudiogram));
    }

    /** @test */
    public function detect_no_significant_change_in_thresholds()
    {
        $baselineAudiogram = factory(Audiogram::class)->state('normal')->create();
        $currentAudiogram  = factory(Audiogram::class)->state('normal')->create();

        $stsd = new StandardThresholdShiftDetermination;

        $this->assertFalse($stsd->test($baselineAudiogram, $currentAudiogram));
    }
}
