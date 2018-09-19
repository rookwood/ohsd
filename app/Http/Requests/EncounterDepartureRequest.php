<?php

namespace App\Http\Requests;

use App\Encounters\EncounterStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EncounterDepartureRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'status' => Rule::in(['arrived']),
        ];
    }

    public function validationData()
    {
        return [
            'status' => EncounterStatus::guess($this->encounter)
        ];
    }

    public function messages()
    {
        return [
            'status.in' => $this->statusErrorMessage(EncounterStatus::guess($this->encounter))
        ];
    }

    protected function statusErrorMessage($status)
    {
        if ($status == 'scheduled') {
            return 'Encounter has not yet been arrived.';
        }

        if ($status == 'departed') {
            return 'Cannot depart an encounter that has already been departed';
        }

        return vsprintf('Cannot depart an encounter from %s status', $status);
    }
}
