<?php

namespace App\Encounters;

use App\Patient;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Makeable\EloquentStatus\HasStatus;

class Encounter extends Model
{
    use HasStatus;

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

    public static function today()
    {
        return static::whereBetween('start_at', [
            Carbon::today()->startOfDay()->toDateTimeString(),
            Carbon::today()->endOfDay()->toDateTimeString()
        ])->get();
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
