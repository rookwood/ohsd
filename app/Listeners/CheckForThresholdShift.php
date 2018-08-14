<?php

namespace App\Listeners;

use App\Events\TestResultWasLogged;
use App\StandardThresholdShiftDetermination;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CheckForThresholdShift
{
    /**
     * @var StandardThresholdShiftDetermination
     */
    private $stsd;

    /**
     * Create the event listener.
     *
     * @param StandardThresholdShiftDetermination $stsd
     */
    public function __construct(StandardThresholdShiftDetermination $stsd)
    {
        $this->stsd = $stsd;
    }

    /**
     * Test for occurence of standard threshold shift.
     *
     * @param  TestResultWasLogged  $event
     * @return void
     */
    public function handle(TestResultWasLogged $event)
    {
        $audiogram = $event->audiogram;

        if ($this->stsd->test($audiogram->getBaseline(), $audiogram)) {
            $audiogram->markAsNewBaseline();
        }

        return;
    }
}
