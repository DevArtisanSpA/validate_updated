<?php

namespace App\Providers;

use URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App as AppFacade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (AppFacade::environment('heroku')) {
            URL::forceScheme('https');
        }
    }
}
