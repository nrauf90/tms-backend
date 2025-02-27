<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class CompanyUserServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            'App\Repository\CompanyUserManagement\CompanyUserManagementInterface',
            'App\Repository\CompanyUserManagement\CompanyUser'
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
