<?php

namespace App\Api\Providers;

use Illuminate\Support\ServiceProvider;

class CsrfServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Api\Contracts\CsrfContract', 'App\Api\Services\CsrfService');
    }
}
