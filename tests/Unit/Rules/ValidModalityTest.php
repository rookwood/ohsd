<?php

namespace Tests\Unit\Rules;

use Tests\TestCase;
use App\Rules\ValidModality;

class ValidModalityTest extends TestCase
{
    /** @test */
    public function valid_modalities_pass()
    {
        $rule = new ValidModality;

        $this->assertTrue($rule->passes('field', 'air'));
    }
}

