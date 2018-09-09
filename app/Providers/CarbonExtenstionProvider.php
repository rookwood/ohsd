<?php

namespace App\Providers;

use App\Support\CarbonAugmentation;
use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;

class CarbonExtenstionProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Carbon::mixin(new CarbonAugmentation());
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
