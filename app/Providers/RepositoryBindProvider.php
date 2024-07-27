<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryBindProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind('App\Interfaces\StrategyWmsInterface', 'App\Repositories\StrategyWmsRepository');
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
