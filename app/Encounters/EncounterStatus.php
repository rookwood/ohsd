<?php

namespace App\Encounters;

use Makeable\EloquentStatus\Status;

class EncounterStatus extends Status
{
    public function scheduled($query)
    {
        return $query->whereNotNull('start_at')
            ->whereNull('arrived_at')
            ->whereNull('cancelled_at');
    }

    public function arrived($query)
    {
        return $query->whereNotNull('start_at')
            ->whereNotNull('arrived_at')
            ->whereNull('departed_at');
    }

    public function departed($query)
    {
        return $query->whereNotNull('start_at')
            ->whereNotNull('arrived_at')
            ->whereNotNull('departed_at');
    }

    public function cancelled($query)
    {
        return $query->whereNotNull('cancelled_at')
            ->whereNull('rescheduled_to');
    }

    public function rescheduled($query)
    {
        return $query->whereNotNull('cancelled_at')
            ->whereNotNull('rescheduled_to');
    }
}
