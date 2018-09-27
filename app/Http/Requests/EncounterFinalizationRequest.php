<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EncounterFinalizationRequest extends FormRequest
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
            'outcome' => ['required'],
            'audiogram' => Rule::requiredIf($this->outcome, 'completed')
        ];
    }

    public function validationData()
    {
        return array_merge($this->all(),
        [
            'audiogram' => $this->encounter->audiogram ?? $this->audiogram_id
        ]);
    }
}
