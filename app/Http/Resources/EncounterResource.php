<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EncounterResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $resource = [
            'start_at' => $this->start_at->format('Y-m-d H:i:s'),
            'patient' => new PatientResource($this->patient),
            'notes' => $this->notes,
            'status' => '',
        ];

        return $resource;
    }
}
