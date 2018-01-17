<?php

namespace App\Api\Providers;

use Illuminate\Support\ServiceProvider;

class HttpServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Api\Contracts\HttpContract', 'App\Api\Services\HttpService');
    }
}
