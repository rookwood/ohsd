<?php

namespace App\Http\Requests;

use App\Rules\ValidAudiometricFrequency;
use App\Rules\ValidAudiometricTest;
use App\Rules\ValidEarOrBinauralStimulusDestination;
use App\Rules\ValidMasking;
use App\Rules\ValidModality;
use App\Rules\ValidStimulus;
use Illuminate\Foundation\Http\FormRequest;

class CreateAudiogramRequest extends FormRequest
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
            'noise_exposure' => ['required'],
            'hearing_protection' => ['required'],
            'otoscopy' => ['required'],
            'date' => ['required', 'date'],
            'responses' => ['required', 'array'],
            'responses.*.frequency' => ['required', 'integer', new ValidAudiometricFrequency],
            'responses.*.ear' => ['required', new ValidEarOrBinauralStimulusDestination],
            'responses.*.amplitude' => ['required', 'integer', 'between:-10,120'],
            'responses.*.stimulus' => ['nullable', new ValidStimulus],
            'responses.*.test' => ['nullable', new ValidAudiometricTest],
            'responses.*.masking' => ['nullable', new ValidMasking],
            'responses.*.modality' => ['nullable', new ValidModality],
            'responses.*.no_response' => ['nullable', 'boolean'],
        ];
    }
}
