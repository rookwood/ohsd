<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AudiologyToolsProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * Change x kHz to x000 or remove Hz
         */
        Str::macro('removeHertzAbbreviation', function($value) {
            if ( ! is_string($value)) {
                return $value;
            }

            $value = preg_replace([
                '/\s?kHz/i',
                '/\s?Hz/i'
            ], [
                '000',
                ''
            ], $value);

            return is_numeric($value) ? (int) $value : $value;
        });
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
