<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class WeekServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            'App\Repository\SaasModules\WeekManagement\WeekManagementInterface',
            'App\Repository\SaasModules\WeekManagement\Week'
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
