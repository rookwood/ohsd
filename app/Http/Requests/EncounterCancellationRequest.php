<?php

namespace App\Http\Requests;

use App\Encounters\EncounterStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EncounterCancellationRequest extends FormRequest
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
            'status' => Rule::in(['scheduled', 'arrived']),
        ];
    }

    public function validationData()
    {
        return ['status' => EncounterStatus::guess($this->encounter)];
    }

    public function messages()
    {
        return [
            'status.in' => 'Encounter already marked as ' . EncounterStatus::guess($this->encounter),
        ];
    }
}
