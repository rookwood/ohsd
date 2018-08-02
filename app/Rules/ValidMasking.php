<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Str;

class ValidMasking implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return $this->isBoolean($value)
            || $this->inMaskingRange(Str::removeHertzAbbreviation($value));
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return ':values is not a valid masking value';
    }

    protected function isBoolean($value)
    {
        return $value === true || $value === false;
    }

    protected function inMaskingRange($value)
    {
        return is_int($value)
            && $value >= -10
            && $value <= 120
        ;
    }
}
