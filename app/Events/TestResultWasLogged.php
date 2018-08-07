<?php

namespace App\Events;

use App\Audiogram;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class TestResultWasLogged
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var Audiogram
     */
    public $audiogram;

    /**
     * Create a new event instance.
     *
     * @param Audiogram $audiogram
     */
    public function __construct(Audiogram $audiogram)
    {
        $this->audiogram = $audiogram;
    }
}
