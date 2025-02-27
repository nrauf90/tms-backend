<?php

namespace App\Repository\SaasModules\ModuleManagement;

use Illuminate\Http\Request;
use App\Models\Module as Modules;

class Module implements ModuleManagementInterface{
    
    public function index(Request $request){
        $modules = Modules::get();

        if($modules->isEmpty()){
            $response["success"] = false;
            $response["message"] = "No results found";
        } else {
            $response["success"] = true;
            $response["message"] = "Results found successfully";
            $response["data"] = $modules;
        }

        return $response;
    }
}