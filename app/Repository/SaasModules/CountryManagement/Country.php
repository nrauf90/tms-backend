<?php

namespace App\Repository\SaasModules\CountryManagement;

use Illuminate\Http\Request;
use App\Models\Country as Countries;

class Country implements CountryManagementInterface{

    public function index(Request $request){
        $countries = Countries::all();

        if($countries->isEmpty()){
            $response["success"] = false;
            $response["message"] = "No results found";
        } else {
            $response["success"] = true;
            $response["message"] = "Results found successfully";
            $response["data"] = $countries;
        }

        return $response;
    }
}