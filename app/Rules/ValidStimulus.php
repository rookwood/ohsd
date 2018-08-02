<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidStimulus implements Rule
{
    static $stimuli = [
        'tone',
        'pulse',
        'fm',
        'fm.pulse',
        'narrowband.noise',
        'speech.noise',
        'white.noise',
        'pink.noise',
        'speech.recording',
        'speech.live',
        'other',
    ];

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return in_array($value, self::$stimuli);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Invalid stimulus';
    }
}
