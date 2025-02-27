<?php

namespace App\Repository\SaasModules\WeekManagement;

use Illuminate\Http\Request;
use App\Models\Day;

class Week implements WeekManagementInterface{

    public function index(Request $request){
        $days = Day::get();

        if($days->isEmpty()){
            $response["success"] = false;
            $response["message"] = "No Results found";
        } else {
            $response["success"] = true;
            $response["message"] = "Results found successfully";
            $response["data"] = $days;
        }

        return $response;
    }
}