<?php

namespace Tests\Unit\Rules;

use Tests\TestCase;
use App\Rules\ValidAudiometricTest;

class ValidAudiometricTestTest extends TestCase
{
    /** @test */
    public function audiometric_tests_pass()
    {
    	$rule = new ValidAudiometricTest;

    	$this->assertTrue($rule->passes('field', 'threshold'));
    	$this->assertTrue($rule->passes('field', 'discrimination'));
    	$this->assertTrue($rule->passes('field', 'mcl'));
    	$this->assertTrue($rule->passes('field', 'ucl'));
    }

    /** @test */
    public function other_values_fail()
    {
        $rule = new ValidAudiometricTest;

        $this->assertFalse($rule->passes('field', 'whispering'));
    }
}
