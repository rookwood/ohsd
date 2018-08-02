<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Rules\ValidStimulus;

class ValidStimulusTest extends TestCase
{
    /** @test */
    public function audiometric_stimuli_pass()
    {
    	$rule = new ValidStimulus;

    	$this->assertTrue($rule->passes('field', 'tone'));
    	$this->assertTrue($rule->passes('field', 'pulse'));
    	$this->assertTrue($rule->passes('field', 'fm'));
    	$this->assertTrue($rule->passes('field', 'fm.pulse'));
    	$this->assertTrue($rule->passes('field', 'narrowband.noise'));
    	$this->assertTrue($rule->passes('field', 'speech.noise'));
    	$this->assertTrue($rule->passes('field', 'white.noise'));
    	$this->assertTrue($rule->passes('field', 'pink.noise'));
    	$this->assertTrue($rule->passes('field', 'speech.recording'));
    	$this->assertTrue($rule->passes('field', 'speech.live'));
    	$this->assertTrue($rule->passes('field', 'other'));
    }

    /** @test */
    public function other_values_fail()
    {
        $rule = new ValidStimulus;

        $this->assertFalse($rule->passes('field', 'crying'));
    }
}
