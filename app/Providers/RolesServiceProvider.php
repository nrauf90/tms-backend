<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RolesServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            'App\Repository\RolesPermissions\RolesManagement\RolesManagementInterface',
            'App\Repository\RolesPermissions\RolesManagement\Roles'
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
