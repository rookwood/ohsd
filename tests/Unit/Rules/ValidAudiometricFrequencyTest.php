<?php

namespace Tests\Unit\Rules;

use Tests\TestCase;
use App\Rules\ValidAudiometricFrequency;

class ValidAudiometricFrequencyTest extends TestCase
{
    /** @test */
    public function frequencies_used_in_audiometry_pass()
    {
        $rule = new ValidAudiometricFrequency;

        $this->assertTrue($rule->passes('field', 1000));
        $this->assertTrue($rule->passes('field', '1kHz'));
        $this->assertTrue($rule->passes('field', '1 kHz'));
        $this->assertTrue($rule->passes('field', 250));
        $this->assertTrue($rule->passes('field', '250 Hz'));
    }

    /** @test */
    public function other_integer_values_fail()
    {
        $rule = new ValidAudiometricFrequency;

        $this->assertFalse($rule->passes('field', 9001));
        $this->assertFalse($rule->passes('field', 251));
    }

    /** @test */
    public function non_integer_values_fail()
    {
    	$rule = new ValidAudiometricFrequency;

    	$this->assertFalse($rule->passes('field', 'text'));
    	$this->assertFalse($rule->passes('field', 'kHz'));
    }
}
