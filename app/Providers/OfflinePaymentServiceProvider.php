<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;


class OfflinePaymentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            'App\Repository\SaasModules\OfflinePaymentManagement\OfflinePaymentManagementInterface',
            'App\Repository\SaasModules\OfflinePaymentManagement\OfflinePayment'
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
