<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class PermissionGroupServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            'App\Repository\RolesPermissions\PermissionGroupManagement\PermissionGroupManagementInterface',
            'App\Repository\RolesPermissions\PermissionGroupManagement\PermissionGroup'
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
