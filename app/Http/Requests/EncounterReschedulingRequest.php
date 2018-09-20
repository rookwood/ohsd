<?php

namespace App\Http\Requests;

use App\Encounters\EncounterStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EncounterReschedulingRequest extends FormRequest
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
            'date' => ['required', 'date'],
            'time' => ['required', 'date_format:H:i:00'],
            'status' => [Rule::in(['scheduled', 'arrived'])]
        ];
    }

    public function validationData()
    {
        return array_merge(
            $this->all(),
            [
                'status' => EncounterStatus::guess($this->encounter)
            ]
        );
    }

    public function messages()
    {
        return [
            'status.in' => $this->statusErrorMessage(EncounterStatus::guess($this->encounter))
        ];
    }

    protected function statusErrorMessage($status)
    {
        if ($status == 'rescheduled') {
            return 'Cannot reschedule an encounter that has already been rescheduled.';
        }

        return vsprintf('Cannot reschedule a %s encounter.', $status);
    }
}
