<?php

namespace App\Encounters;

use Makeable\EloquentStatus\Status;

class EncounterStatus extends Status
{
    public function scheduled($query)
    {
        return $query->whereNotNull('start_at')
            ->whereNull('arrived_at')
            ->whereNull('cancelled_at')
            ->whereNull('rescheduled_to');
    }

    public function arrived($query)
    {
        return $query->whereNotNull('start_at')
            ->whereNotNull('arrived_at')
            ->whereNull('departed_at')
            ->whereNull('cancelled_at')
            ->whereNull('rescheduled_to');
    }

    public function departed($query)
    {
        return $query->whereNotNull('start_at')
            ->whereNotNull('arrived_at')
            ->whereNotNull('departed_at')
            ->whereNull('cancelled_at')
            ->whereNull('rescheduled_to');
    }

    public function cancelled($query)
    {
        return $query->whereNotNull('cancelled_at')
            ->whereNotNull('scheduled_at')
            ->whereNull('arrived_at')
            ->whereNull('rescheduled_to');
    }

    public function rescheduled($query)
    {
        return $query->whereNull('cancelled_at')
            ->whereNotNull('scheduled_at')
            ->whereNotNull('rescheduled_to');
    }

    public function final($query)
    {
        return $query->whereNotNull('finalized_at')
            ->whereNotNull('finalized_by')
            ->whereNotNull('outcome');
    }
}
