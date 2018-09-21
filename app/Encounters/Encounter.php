<?php

namespace App\Encounters;

use App\Audiogram;
use App\Patient;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
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
    ];

    protected $fillable = [
        'start_at',
        'patient_id',
        'notes',
        'arrived_at',
        'cancelled_at',
        'cancellation_reason',
        'departed_at',
        'rescheduled_reason',
        'rescheduled_from',
        'rescheduled_to',
        'rescheduled_at',
        'rescheduled_by',
        'finalized_at',
        'finalized_by',
        'outcome',
        'outcome_notes',
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

    public function depart()
    {
        return tap($this)->update(['departed_at' => Carbon::now()]);
    }

    public function reschedule($date, $time, $reason = null)
    {
        $newEncounter = tap($this->replicate(['scheduled_by']), function ($instance) use ($date, $time, $reason) {
            $instance->save();
            $instance->update([
                'start_at'           => Carbon::fromScheduleRequest($date, $time),
                'scheduled_at'       => Carbon::now(),
                'rescheduled_reason' => $reason,
                'rescheduled_from'   => $this->id,
            ]);
        });

        $this->update([
            'rescheduled_to' => $newEncounter->id,
            'rescheduled_by' => Auth::user()->id,
            'rescheduled_at' => Carbon::now()
        ]);

        return $newEncounter;
    }

    public function finalize($outcome, $notes = null)
    {
        return tap($this)->update([
            'finalized_at' => Carbon::now(),
            'finalized_by' => Auth::user()->id,
            'outcome' => $outcome,
            'outcome_notes' => $notes
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

    public function audiogram()
    {
        return $this->hasOne(Audiogram::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function rescheduledFromEncounter()
    {
        return $this->hasOne(static::class, 'id', 'rescheduled_from');
    }

    public function rescheduledToEncounter()
    {
        return $this->hasOne(static::class, 'id', 'rescheduled_to');
    }
}
