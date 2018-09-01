<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class IntakeFormResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'date' => $this->date,
            'hearing' => $this->hearing,
            'health' => $this->health,
            'allergies' => $this->allergies,
            'diabetes' => $this->diabetes,
            'dizziness' => $this->dizziness,
            'head_injury' => $this->head_injury,
            'hypertension' => $this->hypertension,
            'kidney_disease' => $this->kidney_disease,
            'measles' => $this->measles,
            'mumps' => $this->mumps,
            'scarlet_fever' => $this->scarlet_fever,
            'otorrhea' => $this->otorrhea,
            'otalgia' => $this->otalgia,
            'ear_surgery' => $this->ear_surgery,
            'ear_medications' => $this->ear_medications,
            'tinnitus' => $this->tinnitus,
            'aural_pressure' => $this->aural_pressure,
            'perforated_tympanic_membrane' => $this->perforated_tympanic_membrane,
            'cerumen' => $this->cerumen,
            'ent_consult' => $this->ent_consult,
            'hearing_loss' => $this->hearing_loss,
            'family_history_hearing_loss' => $this->family_history_hearing_loss,
            'use_amplification' => $this->use_amplification,
            'previously_work_noise_exposure' => $this->previously_work_noise_exposure,
            'audiology_consult' => $this->audiology_consult,
            'noise_exposure_recreational_gun_use' => $this->noise_exposure_recreational_gun_use,
            'noise_exposure_power_tools' => $this->noise_exposure_power_tools,
            'noise_exposure_engines' => $this->noise_exposure_engines,
            'noise_exposure_loud_music' => $this->noise_exposure_loud_music,
            'noise_exposure_farm_machinery' => $this->noise_exposure_farm_machinery,
            'noise_exposure_military' => $this->noise_exposure_military,
            'noise_exposure_other' => $this->noise_exposure_other,
            'patient_id' => $this->patient_id,
        ];
    }
}
