<?php

namespace App\Repository\SaasModules\OfflinePaymentManagement;
use Illuminate\Http\Request;

interface OfflinePaymentManagementInterface {
    // discription: This method will be used to load all the offline payment methods
    public function index(Request $request);

    //discription: This method will be used to add new offline payment method
    public function store(Request $request);

    //discription: This method will be used to edit the offline payment method 
    //@param: id of offline payment method which we want to update.
    public function update($id, Request $request);

    //discription: This method will be used to delete the offline payment method.
    public function destroy($id);
}

