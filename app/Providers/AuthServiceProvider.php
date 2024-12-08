<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Registrar los servicios de la aplicación.
     *
     * @return void
     */
    public function register()
    {
        Passport::ignoreRoutes();
    }

    /**
     * Bootstrap los servicios de la aplicación.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

    }
}
