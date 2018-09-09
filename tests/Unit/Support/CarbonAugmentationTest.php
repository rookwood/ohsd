<?php

namespace Tests\Unit\Support;

use Carbon\Carbon;
use Tests\TestCase;

class CarbonAugmentationTest extends TestCase
{
   /** @test */
   public function parse_separate_date_and_time_variables()
   {
       $carbonA = Carbon::fromScheduleRequest('2018-11-06', '7:30 am');
       $carbonB = Carbon::fromScheduleRequest('2019-01-03', '5:30 pm');

       $this->assertEquals('2018-11-06 07:30:00', $carbonA->toDateTimeString());
       $this->assertEquals('2019-01-03 17:30:00', $carbonB->toDateTimeString());
   }
}
