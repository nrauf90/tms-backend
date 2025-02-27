<?php

namespace App\Repository\SaasModules\OfflinePaymentManagement;

use Illuminate\Http\Request;
use App\Models\OfflinePaymentMethod;

class OfflinePayment implements OfflinePaymentManagementInterface{
    
    public function index(Request $request){
        if($request->methodName == null){
            $methods = OfflinePaymentMethod::paginate($request->length);

            if($methods->isEmpty()){
                $response["success"] = false;
                $response["message"] = "No results found";
            } else {
                $response["success"] = true;
                $response["message"] = "Results found successfully";
                $response["data"] = $methods;
            }
        } else {
            $methodName = $request->methodName;
            $methods = OfflinePaymentMethod::where(function ($query) use ($methodName){
                if($methodName != null){
                    return $query->where('method_name', 'LIKE', '%'.$methodName.'%');
                }
            })->paginate($request->length);

            if(empty($methods)){
                $response["success"] = false;
                $response["message"] = "No results found";
            } else {
                $response["success"] = true;
                $response["message"] = "Results found successfully";
                $response["data"] = $methods;
            }
        }
        
        return $response;
    }

    public function store(Request $request){
        $check = OfflinePaymentMethod::where('method_name', $request->data['methodName'])
        ->where('description', $request->data['methodDescription'])
        ->where('status', $request->data['methodStatus'])
        ->get();

        if($check->isEmpty()){
            $newMethod = new OfflinePaymentMethod;
            $newMethod->method_name = $request->data['methodName'];
            $newMethod->description = $request->data['methodDescription'];
            $newMethod->status = $request->data['methodStatus'];
            $newMethod->save();

            if(! $newMethod){
                $response["success"] = false;
                $response["message"] = "Data not saved";
            } else {
                $response["success"] = true;
                $response["message"] = "Data has been saved successfully";
                $response["data"] = $newMethod;
            }
        } else {
            $response["success"] = false;
            $response["message"] = "This method already exists";
        }

        return $response;
    }

    public function update($id, Request $request){
        $method = OfflinePaymentMethod::find($id);

        if(empty($method)){
            $response["success"] = false;
            $response["message"] = "No results found";
        } else {
            $method->method_name = $request->data['methodName'];
            $method->description = $request->data['methodDescription'];
            $method->status = $request->data['methodStatus'];
            $method->save();

            if(!$method){
                $response["success"] = false;
                $response["message"] = "Unable to update method";
            } else {
                $response["success"] = true;
                $response["message"] = "Method updated successfully";
                $response["data"] = $method;
            }
        }

        return $response;
    }

    public function destroy($id){
        $method = OfflinePaymentMethod::find($id);

        if(empty($method)){
            $response["success"] = false;
            $response["message"] = "No results found";
        } else {
            $method->delete();
            $response["success"] = true;
            $response["message"] = "Method deleted successfully";
            $response["data"] = $method;
        }

        return $response;
    }
}