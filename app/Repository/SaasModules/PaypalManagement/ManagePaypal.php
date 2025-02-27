<?php

namespace App\Repository\SaasModules\PaypalManagement;

use App\Models\PaypalCredential;
use Illuminate\Http\Request;

class ManagePaypal implements PaypalManagementInterface{

    public function store(Request $request){
        $check = PaypalCredential::where('paypal_client_id', $request->paypal_client_id)
        ->where('paypal_secret', $request->paypal_secret)
        ->where('paypal_environment', $request->paypal_environemnt)
        ->where('webhook_secret', $request->webhook_secret)
        ->get();

        if($check->isEmpty()){
            $credentials = new PaypalCredential;
            $credentials->paypal_client_id = $request->paypal_client_id;
            $credentials->paypal_secret = $request->paypal_secret;
            $credentials->paypal_environment = $request->paypal_environment;
            $credentials->webhook_secret = $request->webhook_secret;
            $credentials->status = $request->status;
            $credentials->save();

            if(! $credentials){
                $response["success"] = false;
                $response["message"] = "Data not saved";
            } else {
                $response["success"] = true;
                $response["message"] = "Data has been saved successfully";
                $response["data"] = $credentials;
            }
        } else {
            $response["success"] = false;
            $response["message"] = "Unable to save data";
        }
        
        return $response;
    }

    public function getPaypalGatewayInformation(){
        $credentials = PaypalCredential::get();

        if($credentials->isEmpty()){
            $response["success"] = false;
            $response["message"] = "No Results found";
        } else {
            $response["success"] = true;
            $response["message"] = "Results found successfully";
            $response["data"] = $credentials;
        }

        return $response;
    }

    public function update($id, Request $request){
        $credentials = PaypalCredential::find($id);

        if(empty($credentials)){
            $response["success"] = false;
            $response["message"] = "No data found";
        } else {
            $updateCredentials = PaypalCredential::where('id', $id)
            ->update([
                'paypal_client_id' => $request->paypal_client_id,
                'paypal_secret'    => $request->paypal_secret,
                'paypal_environment'    => $request->paypal_environment,
                'webhook_secret'    => $request->webhook_secret,
                'status'    => $request->status,
            ]);

            if(! $updateCredentials){
                $response["success"] = false;
                $response["message"] = "Unable to update record";
            } else {
                $response["success"] = true;
                $response["message"] = "Record Updated Successfully";
                $response["data"] = $updateCredentials;
            }
        }

        return $response;

    }


}