<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class IntakeForm extends Model
{
    protected $fillable = [
        'date',
        'hearing',
        'health',
        'allergies',
        'diabetes',
        'dizziness',
        'head_injury',
        'hypertension',
        'kidney_disease',
        'measles',
        'mumps',
        'scarlet_fever',
        'otorrhea',
        'otalgia',
        'ear_surgery',
        'ear_medications',
        'tinnitus',
        'aural_pressure',
        'perforated_tympanic_membrane',
        'cerumen',
        'ent_consult',
        'hearing_loss',
        'family_history_hearing_loss',
        'use_amplification',
        'previously_work_noise_exposure',
        'audiology_consult',
        'noise_exposure_recreational_gun_use',
        'noise_exposure_power_tools',
        'noise_exposure_engines',
        'noise_exposure_loud_music',
        'noise_exposure_farm_machinery',
        'noise_exposure_military',
        'noise_exposure_other',
        'patient_id',
    ];

    protected $casts = [
        'date' => 'date:Y-m-d',
        'allergies' => 'boolean',
        'diabetes' => 'boolean',
        'dizziness' => 'boolean',
        'head_injury' => 'boolean',
        'hypertension' => 'boolean',
        'kidney_disease' => 'boolean',
        'measles' => 'boolean',
        'mumps' => 'boolean',
        'scarlet_fever' => 'boolean',
        'otorrhea' => 'boolean',
        'otalgia' => 'boolean',
        'ear_surgery' => 'boolean',
        'ear_medications' => 'boolean',
        'tinnitus' => 'boolean',
        'aural_pressure' => 'boolean',
        'perforated_tympanic_membrane' => 'boolean',
        'cerumen' => 'boolean',
        'ent_consult' => 'boolean',
        'use_amplification' => 'boolean',
        'previously_work_noise_exposure' => 'boolean',
        'audiology_consult' => 'boolean',
        'noise_exposure_recreational_gun_use' => 'boolean',
        'noise_exposure_power_tools' => 'boolean',
        'noise_exposure_engines' => 'boolean',
        'noise_exposure_loud_music' => 'boolean',
        'noise_exposure_farm_machinery' => 'boolean',
        'noise_exposure_military' => 'boolean',
        'noise_exposure_other' => 'boolean',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'date',
    ];

    public static function registerPatient(Patient $patient, $registration)
    {
        return $patient->intakeForms()->save(new static(
            array_merge($registration, ['date' => Carbon::today()])
        ));
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
