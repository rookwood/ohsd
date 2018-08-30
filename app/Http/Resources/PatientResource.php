<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PatientResource extends JsonResource
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
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'mrn' => $this->mrn,
            'birthdate' => $this->birthdate->format('Y-m-d'),
            'gender' => $this->gender,
            'hire_date' => $this->hire_date,
            'title' => $this->title,
            'employee_id' => $this->employee_id,
            'employer' => $this->employer,
            'audiograms' => $this->audiograms->map(function($audiogram) {
                return new AudiogramResource($audiogram);
            })
        ];
    }
}
