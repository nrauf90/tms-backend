<?php

namespace App\Repository\SaasModules\StripeManagement;

use Illuminate\Http\Request;

interface StripeManagementInterface {

    //discription: This method will be used to store Stripe Information for a particular user.
    public function store(Request $request);

    //discription: This method will be used to get the Stripe Information for a particular user.
    public function getStripeGatewayInformation();

    //discription: This method will be used to update the Stripe Information for a particular user.
    public function update($id, Request $request);

}