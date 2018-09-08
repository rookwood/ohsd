<?php

namespace App\Encounters;

use Makeable\EloquentStatus\Status;

class EncounterStatus extends Status
{
    public function scheduled($query)
    {
        return $query->whereNotNull('start_at')
            ->whereNull('arrived_at')
            ->whereNull('departed_at')
            ->whereNull('cancelled_at')
            ->whereNull('rescheduled_to');
    }
}
