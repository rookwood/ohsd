<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Encounter extends Model
{
    protected $dates = [
        'created_at',
        'updated_at',
        'start_at',
        'cancelled_at',
        'rescheduled_at',
        'arrived_at',
        'departed_at',
        'rescheduled_to',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
