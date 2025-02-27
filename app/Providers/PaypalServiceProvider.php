<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class PaypalServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            'App\Repository\SaasModules\PaypalManagement\PaypalManagementInterface',
            'App\Repository\SaasModules\PaypalManagement\ManagePaypal'
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
