<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidEarOrBinauralStimulusDestination implements Rule
{
    protected static $sources = [
        'right',
        'left',
        'both',
        'soundfield',
        'aided',
        'ci',
        'unknown'
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
        return in_array($value, self::$sources);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return ':value is not a valid ear or stimulus source.';
    }
}
