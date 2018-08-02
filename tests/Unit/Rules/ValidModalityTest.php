<?php

namespace Tests\Unit\Rules;

use App\Rules\ValidModality;
use Tests\TestCase;

class ValidModalityTest extends TestCase
{
    /** @test */
    public function valid_modalities_pass()
    {
        $rule = new ValidModality;

        $this->assertTrue($rule->passes('field', 'air'));
    }
}

