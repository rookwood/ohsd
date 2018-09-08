<?php

namespace App\Http\Resources;

use App\Encounters\EncounterStatus;
use Illuminate\Http\Resources\Json\JsonResource;

class EncounterResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     * @throws \Exception
     */
    public function toArray($request)
    {
        return [
            'start_at' => $this->start_at->format('Y-m-d H:i:s'),
            'patient' => new PatientResource($this->patient),
            'notes' => $this->notes,
            'status' => EncounterStatus::guess($this->resource),
        ];
    }
}
