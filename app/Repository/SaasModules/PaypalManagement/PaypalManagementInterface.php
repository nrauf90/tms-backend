<?php

namespace App\Repository\SaasModules\PaypalManagement;

use Illuminate\Http\Request;

interface PaypalManagementInterface{
    //discription: This method will be used to store Stripe Information for a particular user.
    public function store(Request $request);

    //discription: This method will be used to get the Stripe Information for a particular user.
    public function getPaypalGatewayInformation();

    //discription: This method will be used to update the Stripe Information for a particular user.
    public function update($id, Request $request);

}