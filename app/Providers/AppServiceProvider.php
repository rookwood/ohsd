<?php

namespace App\Providers;

use App\Audiogram;
use App\Encounters\Encounter;
use App\Observers\AudiogramObserver;
use App\Observers\EncounterObserver;
use App\Observers\UserObserver;
use App\Users\User;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        User::observe(UserObserver::class);
        Audiogram::observe(AudiogramObserver::class);
        Encounter::observe(EncounterObserver::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
