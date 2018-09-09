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
        return static::forTimeSpan(
            Carbon::today()->startOfDay()->toDateTimeString(),
            Carbon::today()->endOfDay()->toDateTimeString()
        );
    }

    public static function nextSevenDays()
    {
        return static::forTimeSpan(
            Carbon::today()->startOfDay()->toDateTimeString(),
            Carbon::today()->addDays(7)->endOfDay()->toDateTimeString()
        );
    }

    public static function forTimeSpan($start, $end)
    {
        return static::whereBetween('start_at', [$start, $end]);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
