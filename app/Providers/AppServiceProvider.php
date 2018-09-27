<?php

namespace App\Providers;

use App\Audiogram;
use App\Encounters\Encounter;
use App\Encounters\EncounterStatus;
use App\Observers\AudiogramObserver;
use App\Observers\EncounterObserver;
use App\Observers\PatientObserver;
use App\Observers\UserObserver;
use App\Patient;
use App\Users\User;
use Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider;
use Illuminate\Support\ServiceProvider;
use Makeable\EloquentStatus\StatusManager;

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
        Patient::observe(PatientObserver::class);

        StatusManager::bind(Encounter::class, EncounterStatus::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if( $this->app->environment() !== 'production') {
            $this->app->register(IdeHelperServiceProvider::class);
        }
    }
}
