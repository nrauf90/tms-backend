<?php

namespace App\Repository\SaasModules\PackageManagement;

use Illuminate\Http\Request;
use App\Models\Package as Packages;
use App\Models\PackageHasModules;

class Package implements PackageManagementInterface {

    public function index(Request $request){

        if($request->packageName == null){
            $packages = Packages::with('modules')->paginate($request->length);

            if($packages->isEmpty()){
                $response["success"] = false;
                $response["message"] = "No results found";
            } else {
                $response["success"] = true;
                $response["message"] = "Results found successfully";
                $response["data"] = $packages;
            }
        } else {
            $package = $request->packageName;
            $packages = Packages::where(function ($query) use ($package){
                return $query->where('name', 'LIKE', '%'.$package.'%');
            })->with('modules')
            ->paginate($request->length);
            if(empty($packages)){
                $response["success"] = false;
                $response["message"] = "No results found";
            } else {
                $response["success"] = true;
                $response["message"] = "Results found successfully";
                $response["data"] = $packages;
            }
        }

        return $response;

    }

    public function store(Request $request){
        $check = Packages::where('name' , $request->name)->get();

        
        if($check->isEmpty()){
            if($request->is_default == 1){
                $defaultPackage = Packages::where('is_default', 1)->update(['is_default' => 0]);
            }
            $package = new Packages;
            $package->name = $request->name;
            $package->employees = $request->employees;
            if($request->storageSize == 0){
                $package->storage = "Unlimited";
            } else{
            $package->storage = $request->storageSize." ".$request->storageUnit;
            }
            $package->is_default = $request->is_default;
            if($request->free_plan == 1){
                $package->free_plan = $request->free_plan;
                $package->discription = $request->discription;
                $package->is_private = $request->is_private;
                $package->is_recommended = $request->is_recommended;
                $package->save();

                //For Modules
                if($request->filled('modules')){
                for($i=0; $i<count($request->modules); $i++) {
                    $packageModule = new PackageHasModules;
                    $packageModule->package_id = $package->id;
                    $packageModule->module_id = $request->modules[$i];
                    $packageModule->save();
                }
            }

                if(! $package){
                    $response["success"] = false;
                    $response["message"] = "Data not saved";
                }
                else {
                    $response["success"] = true;
                    $response["message"] = "Data has been saved successfully";
                    $response["data"] = $package;
                }
            } else {
                if($request->filled('monthly_price')){
                    $package->monthly_price = $request->monthly_price;
                    $package->stripe_monthly_plan_id = $request->stripe_monthly_plan_id;
                    $package->paypal_monthly_plan_id = $request->paypal_monthly_plan_id;
                }
                if($request->filled('annual_price')){
                    $package->annual_price = $request->annual_price;
                    $package->stripe_annual_plan_id = $request->stripe_annual_plan_id;
                    $package->paypal_annual_plan_id = $request->paypal_annual_plan_id;
                }
                $package->free_plan = $request->free_plan;
                $package->discription = $request->discription;
                $package->is_private = $request->is_private;
                $package->is_recommended = $request->is_recommended;
                $package->save();

                //For Modules
                if($request->filled('modules')){
                for($i= 0; $i<count($request->modules); $i++) {
                    $packageModule = new PackageHasModules;
                    $packageModule->package_id = $package->id;
                    $packageModule->module_id = $request->modules[$i];
                    $packageModule->save();
                }
            }

                if(! $package){
                    $response["success"] = false;
                    $response["message"] = "Data not saved";
                }
                else {
                    $response["success"] = true;
                    $response["message"] = "Data has been saved successfully";
                    $response["data"] = $package;
                }
            }
        } else {
            $response["success"] = false;
            $response["message"] = "Unable to save data";
        }

        return $response;
    }

    public function update($id, Request $request){
        $package = Packages::find($id);

        if(empty($package)){
            $response["success"] = false;
            $response["message"] = "Unable to update package";
        } else {
            $package->name = $request->name;
            $package->employees = $request->employees;
            if($request->storageSize == 0){
                $package->storage = "Unlimited";
            } else {
            $package->storage = $request->storageSize." ".$request->storageUnit;
            }
            if($request->is_default == 1){
                $defaultPackage = Packages::where('is_default', 1)->update(['is_default' => 0]);
            }
            $package->is_default = $request->is_default;
            $package->monthly_price = null;
            $package->stripe_monthly_plan_id = null;
            $package->paypal_monthly_plan_id = null;
            $package->annual_price = null;
            $package->stripe_annual_plan_id = null;
            $package->paypal_annual_plan_id = null;

            if($request->free_plan == 1){
                $package->free_plan = $request->free_plan;
                $package->discription = $request->discription;
                $package->is_private = $request->is_private;
                $package->is_recommended = $request->is_recommended;
                $package->save();
                //Deleting Package Modules then we will add updated modules record in for loop
                $modulesCheck = PackageHasModules::where('package_id', $id)->get();
                if($modulesCheck->isNotEmpty()){
                $modules = PackageHasModules::where('package_id', $id)->delete();
                }
                //For Modules
                if($request->filled('modules')){
                for($i= 0; $i<count($request->modules); $i++) {
                    $packageModule = new PackageHasModules;
                    $packageModule->package_id = $package->id;
                    $packageModule->module_id = $request->modules[$i];
                    $packageModule->save();
                }
            }
                if(! $package){
                    $response["success"] = false;
                    $response["message"] = "Unable to update package";
                }
                else {
                    $response["success"] = true;
                    $response["message"] = "Package has been updated successfully";
                    $response["data"] = $package;
                }
            } else {
                if($request->filled('monthly_price')){
                    $package->monthly_price = $request->monthly_price;
                    $package->stripe_monthly_plan_id = $request->stripe_monthly_plan_id;
                    $package->paypal_monthly_plan_id = $request->paypal_monthly_plan_id;
                }
                if($request->filled('annual_price')){
                    $package->annual_price = $request->annual_price;
                    $package->stripe_annual_plan_id = $request->stripe_annual_plan_id;
                    $package->paypal_annual_plan_id = $request->paypal_annual_plan_id;
                }
                $package->free_plan = $request->free_plan;
                $package->discription = $request->discription;
                $package->is_private = $request->is_private;
                $package->is_recommended = $request->is_recommended;
                $package->save();

                //Deleting Modules....
                $modulesCheck = PackageHasModules::where('package_id', $id)->get();
                if($modulesCheck->isNotEmpty()){
                $modules = PackageHasModules::where('package_id', $id)->delete();
                }
                //For Modules
                if($request->filled('modules')){
                for($i= 0; $i<count($request->modules); $i++) {
                    $packageModule = new PackageHasModules;
                    $packageModule->package_id = $package->id;
                    $packageModule->module_id = $request->modules[$i];
                    $packageModule->save();
                }
            }
                if(! $package){
                    $response["success"] = false;
                    $response["message"] = "Data not saved";
                }
                else {
                    $response["success"] = true;
                    $response["message"] = "Data has been saved successfully";
                    $response["data"] = $package;
                }
            }
        }

        return $response;
    }

    public function destroy($id){
        $package = Packages::find($id);

        if(empty($package)){
            $response["success"] = false;
            $response["message"] = "No results found";
        } else {
            $package->delete();
            $response["success"] = true;
            $response["message"] = "Package deleted successfully";
            $response["data"] = $package;
        }

        return $response;
    }

    public function details($id){
        $package = Packages::where('id', $id)->with('modules')->get();
        
        if($package->isEmpty()){
            $response["success"] = false;
            $response["message"] = "No Results found";
        } else {
            $response["success"] = true;
            $response["message"] = "Results found";
            $response["data"] = $package;
        }

        return $response;
    }

    public function allPackages(){
        $packages = Packages::all();

        if($packages->isEmpty()){
            $response["success"] = false;
            $response["message"] = "No Results found";
        } else {
            $response["success"] = true;
            $response["message"] = "Results found successfully";
            $response["data"] = $packages;
        }

        return $response;
    }
}