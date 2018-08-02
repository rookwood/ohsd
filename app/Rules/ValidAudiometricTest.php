<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidAudiometricTest implements Rule
{
    protected static $tests = [
        'threshold',
        'discrimination',
        'mcl',
        'ucl',
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
        return in_array($value, self::$tests);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Invalid test type';
    }
}
