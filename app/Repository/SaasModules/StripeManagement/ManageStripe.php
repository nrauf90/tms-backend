<?php

namespace App\Repository\SaasModules\StripeManagement;

use Illuminate\Http\Request;
use App\Models\StripeCredential;

class ManageStripe implements StripeManagementInterface{

    public function getStripeGatewayInformation(){
        $credentials = StripeCredential::get();

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

    public function store(Request $request){
        $check = StripeCredential::where('publishable_key', $request->publishable_key)
        ->where('secret_key', $request->secret_key)
        ->where('webhook_secret', $request->webhook_secret)->get();

        if($check->isEmpty()){
            $credentials = new StripeCredential;
            $credentials->publishable_key = $request->publishable_key;
            $credentials->secret_key = $request->secret_key;
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

    public function update($id, Request $request){
        $credentials = StripeCredential::find($id);

        if(empty($credentials)){
            $response["success"] = false;
            $response["message"] = "No data found";
        } else {
            $updateCredentials = StripeCredential::where('id', $id)
            ->update([
                'publishable_key' => $request->publishable_key,
                'secret_key'    => $request->secret_key,
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

