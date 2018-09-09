<?php

namespace App\Support;

use Carbon\Carbon;

class CarbonAugmentation
{
    public static function fromScheduleRequest()
    {
        return function($date, $time) {
            return Carbon::parse($date . ' ' . $time);
        };
    }
}
