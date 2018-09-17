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

    protected $fillable = [
        'start_at',
        'patient_id',
        'notes',
        'arrived_at',
        'cancelled_at',
        'cancellation_reason',
    ];

    public static function schedule(Patient $patient, array $details)
    {
        $startTime = Carbon::fromScheduleRequest($details['date'], $details['time']);

        return static::create(array_merge($details, [
            'patient_id' => $patient->id,
            'start_at' => $startTime
        ]));
    }

    public function arrive()
    {
        return tap($this)->update(['arrived_at' => Carbon::now()]);
    }

    public function cancel($reason = null)
    {
        return tap($this)->update([
            'cancelled_at' => Carbon::now(),
            'cancellation_reason' => $reason
        ]);
    }

    public function today()
    {
        return $this->forTimeSpan(
            Carbon::today()->startOfDay()->toDateTimeString(),
            Carbon::today()->endOfDay()->toDateTimeString()
        );
    }

    public function nextSevenDays()
    {
        return $this->forTimeSpan(
            Carbon::today()->startOfDay()->toDateTimeString(),
            Carbon::today()->addDays(7)->endOfDay()->toDateTimeString()
        );
    }

    public function forTimeSpan($start, $end)
    {
        return $this->whereBetween('start_at', [$start, $end])->get();
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
