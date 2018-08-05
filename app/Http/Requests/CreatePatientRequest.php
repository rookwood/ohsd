<?php

namespace App\Http\Requests;

use LVR\Phone\Phone;
use LVR\State\Abbr as ValidState;
use Illuminate\Foundation\Http\FormRequest;

class CreatePatientRequest extends FormRequest
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
     * @throws \Exception
     */
    public function rules()
    {
        return [
            'firstname' => ['required'],
            'lastname' => ['required'],
            'mrn' => ['nullable', 'integer'],
            'birthdate' => ['required', 'date'],
            'employer_id' => ['nullable', 'required_without:employer'],
            'employer' => ['nullable', 'required_without:employer_id', 'array'],
            'employer.name' => ['nullable', 'required_without:employer_id'],
            'employer.email' => ['nullable', 'email'],
            'employer.state' => ['nullable', new ValidState],
            'employer.phone' => ['nullable', new Phone],
        ];
    }
}
