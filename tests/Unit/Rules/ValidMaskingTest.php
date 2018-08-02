<?php

namespace Tests\Unit\Rules;

use Tests\TestCase;
use App\Rules\ValidMasking;

class ValidMaskingTest extends TestCase
{
    /** @test */
    public function boolean_values_pass()
    {
        $rule = new ValidMasking;

        $this->assertTrue($rule->passes('field', true));
        $this->assertTrue($rule->passes('field', false));
    }

    /** @test */
    public function masking_values_used_in_clinical_practice_pass()
    {
    	$rule = new ValidMasking;

    	$this->assertTrue($rule->passes('field', -10));
    	$this->assertTrue($rule->passes('field', 50));
    	$this->assertTrue($rule->passes('field', 120));
    	$this->assertTrue($rule->passes('field', '20'));
    	$this->assertFalse($rule->passes('field', 121));
    	$this->assertFalse($rule->passes('field', -11));
    }

    /** @test */
    public function other_values_do_not_pass()
    {
    	$rule = new ValidMasking;

    	$this->assertFalse($rule->passes('field', 'some text'));
    	$this->assertFalse($rule->passes('field', []));
    }
}
