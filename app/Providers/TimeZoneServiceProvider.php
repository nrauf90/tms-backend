<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repository\SaasModules\TimeZoneManagement\TimeZoneManagementInterface;
use App\Repository\SaasModules\TimeZoneManagement\TimeZone;


class TimeZoneServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {}

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(
            'App\Repository\SaasModules\TimeZoneManagement\TimeZoneManagementInterface',
            'App\Repository\SaasModules\TimeZoneManagement\TimeZone'
        );
    }
}
