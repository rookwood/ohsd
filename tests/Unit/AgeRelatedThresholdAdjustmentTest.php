<?php

namespace Tests\Unit;

use App\AgeRelatedThresholdAdjustment;
use App\Audiogram;
use App\Patient;
use Carbon\Carbon;
use Tests\TestCase;

class AgeRelatedThresholdAdjustmentTest extends TestCase
{
    /** @test */
    public function get_threshold_adjustments_for_a_patient()
    {
        $baseline = new Audiogram(['date' => Carbon::now()->subYears(4)]);
        $current  = new Audiogram(['date' => Carbon::today()]);

        // Age 40 male
        $male = new Patient([
            'gender' => 'male',
            'birthdate' => Carbon::now()->subYears(40)->subDays(7),
        ]);

    	// Age 48 female
        $female = new Patient([
            'gender' => 'female',
            'birthdate' => Carbon::now()->subYears(48)->subDays(7),
        ]);

        $adjuster = new AgeRelatedThresholdAdjustment;

        $this->assertEquals(
            [1000 => 0, 2000 => 1, 3000 => 1, 4000 => 2, 6000 => 3],
            $adjuster->forPatient($male, $baseline, $current)
        );
        $this->assertEquals(
            [1000 => 1, 2000 => 1, 3000 => 2, 4000 => 2, 6000 => 2],
            $adjuster->forPatient($female, $baseline, $current)
        );
    }

    /** @test */
    public function pick_gender_for_adjustments_based_on_user_supplied_data()
    {
        $adjuster = new AgeRelatedThresholdAdjustment;

        $this->assertEquals('male', $adjuster->genderAssignment('male'));
        $this->assertEquals('female', $adjuster->genderAssignment('female'));
        $this->assertEquals('male', $adjuster->genderAssignment('transgender-male'));
        $this->assertEquals('female', $adjuster->genderAssignment('transgender-female'));
        $this->assertEquals('male', $adjuster->genderAssignment('non-binary'));
        $this->assertEquals('male', $adjuster->genderAssignment('other'));
        $this->assertEquals('male', $adjuster->genderAssignment('undisclosed'));
    }

    /** @test */
    public function patients_age_on_date_of_test()
    {
    	$birthdate = Carbon::parse('Oct 10, 1970');
    	$testDate = Carbon::parse('Sept 29, 2017');

    	$adjustment = new AgeRelatedThresholdAdjustment;

    	$this->assertEquals(46, $adjustment->ageAt($birthdate, $testDate));
    }

    /** @test */
    public function age_adjustment_is_bounded_by_available_data()
    {
    	$birthdateA = Carbon::parse('Oct 10, 2000');
    	$birthdateB = Carbon::parse('Oct 10, 1940');
    	$testDate = Carbon::parse('Sept 29, 2017');

        $adjustment = new AgeRelatedThresholdAdjustment;

        $this->assertEquals(20, $adjustment->ageAt($birthdateA, $testDate));
        $this->assertEquals(60, $adjustment->ageAt($birthdateB, $testDate));
    }
}
