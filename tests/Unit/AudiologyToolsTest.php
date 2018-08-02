<?php

namespace Tests\Unit;

use Illuminate\Support\Str;
use Tests\TestCase;

class AudiologyToolsTest extends TestCase
{
    /** @test */
    public function remove_frequency_abbreviations()
    {
        $this->assertEquals('1000', Str::removeHertzAbbreviation('1 kHz'));
        $this->assertEquals('250', Str::removeHertzAbbreviation('250 Hz'));
        $this->assertEquals('16000', Str::removeHertzAbbreviation('16kHz'));
        $this->assertEquals('9999', Str::removeHertzAbbreviation('9999Hz'));
    }

    /** @test */
    public function do_not_process_values_that_are_not_strings()
    {
        $this->assertEquals(-10, Str::removeHertzAbbreviation(-10));
        $this->assertEquals([], Str::removeHertzAbbreviation([]));
    }
}
