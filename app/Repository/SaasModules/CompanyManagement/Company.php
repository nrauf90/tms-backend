<?php

namespace App\Repository\SaasModules\CompanyManagement;

use Illuminate\Http\Request;
use App\Models\Company as Companies;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\Roles;
use App\Models\CompanyDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Company implements CompanyManagementInterface {
    
    public function index(Request $request){
        if($request->package == null and 
        $request->email == null and 
        $request->company == null and
        $request->status == null){
            $companies = Companies::with(
            'package',
            'companyDetail',
            'companyDetail.country',
            'companyDetail.language',
            'companyDetail.timezone',
            'companyDetail.currency'
            )->paginate($request->length);

            if($companies->isEmpty()){
                $response["success"] = false;
                $response["message"] = "No results found";
            } else {
                $response["success"] = true;
                $response["message"] = "Results found successfully";
                $response["data"] = $companies;
            }
        } else {
            $companies = Companies::where(function ($query) use ($request){
                if($request->company != null){
                    return $query->where('name', 'LIKE', '%'.$request->company.'%');
                }
            })
            ->whereHas('companyDetail', function ($query) use ($request){
                if($request->email != null){
                    return $query->where('email', 'LIKE', '%'.$request->email.'%');
                }
            })
            ->where(function ($query) use ($request){
                if($request->status != null){
                return $query->where('status', $request->status);
                }
            })
            ->where(function ($query) use ($request){
                if($request->package != null){
                    return $query->where('package_id', $request->package);
                }
            })
            ->with(
                'companyDetail',
                'companyDetail.country',
                'companyDetail.language',
                'companyDetail.timezone',
                'companyDetail.currency'
            )
            ->paginate($request->length);

            if($companies->isEmpty()){
                $response["success"] = false;
                $response["message"] = "No Results found";
            } else {
                $response["success"] = true;
                $response["message"] = "Results found successfully";
                $response["data"] = $companies;
            }
        }

        return $response;
    }

    public function store(Request $request){
        $companyCheck = Companies::where('name', $request->companyName)
        ->get();
        if($companyCheck->isEmpty()){
            $userCheck = User::where('email', $request->email)->get();
            if($userCheck->isEmpty()){
                $user = new User;
                $user->email = $request->email;
                $user->password = Hash::make($request->password);
                $username = explode('@', $request->email);
                $user->username = $username[0];
                $user->fname = $request->fname;
                $user->lname = $request->lname;
                $user->image = "images/users/user_placeholder.png";
                $user->save();
                if(! $user){
                    $response["success"] = false;
                    $response["message"] = "Unable to create admin user";
                } else {
                $role = Roles::where('name', 'Company Admin')->first();
                $user->assignRole($role->id);
                $company = new Companies;
                $company->name = $request->companyName;
                $company->website = $request->companyWebsite;
                $company->admin_id = $user->id;
                $company->package_id = $request->defaultPackage;
                $company->status = $request->status;
                $company->save();
                $companyDetail = new CompanyDetail;
                $companyDetail->company_id = $company->id;
                $companyDetail->address_line_1 = $request->companyAddress;
                $companyDetail->currency_id = $request->defaultCurrency;
                $companyDetail->timezone_id = $request->defaultTimezone;
                $companyDetail->language_id = $request->defaultLanguage;
                $companyDetail->email = $request->companyEmail;
                $companyDetail->phone = $request->companyPhone;
                $companyDetail->logo = $this->uploadImage($request->logo, 'companyLogos'); //returns the path of image
    
                $companyDetail->save();
                if(! $company){
                    $response["success"] = false;
                    $response["message"] = "Failed to create company";
                } else {
                    $response["success"] = true;
                    $response["message"] = "Company created successfully";
                    $response["data"] = $company;
                }
                }
            } else {
                $response["success"] = false;
                $response["message"] = "User with this email already exists";
            }
        } else {
            $response["success"] = false;
            $response["message"] = "Company with these credentials already exists";
        }

        return $response;

    }

    public function update($id, Request $request){
        $company = Companies::find($id);
        if(empty($company)){
            $response["success"] = false;
            $response["message"] = "No results found";
        } else {
            $company->name = $request->companyName;
            $company->save();
            $companyDetail = CompanyDetail::where('company_id', $id)->first();
            $companyDetail->email = $request->email;
            $companyDetail->phone = $request->phone;
            $companyDetail->address_line_1 = $request->address1;
            $companyDetail->address_line_2 = $request->address2;
            $companyDetail->slug = $request->slug;
            $companyDetail->organzation_type = $request->organizationType;
            $companyDetail->country_id = $request->country;
            $companyDetail->city = $request->city;
            $companyDetail->state = $request->state;
            $companyDetail->zip = $request->zip;
            $companyDetail->contact_person = $request->contactPerson;
            $companyDetail->fax = $request->fax;
            $companyDetail->ntn = $request->ntn;
            $companyDetail->cnic = $request->cnic;
            //$companyDetail->logo = $request->companyLogo;
            //$companyDetail->currency_id = $request->currency;
            //$companyDetail->timezone_id = $request->timezone;
            //$companyDetail->language_id = $request->language;
            $companyDetail->save();

            $response["success"] = true;
            $response["message"] = "Updated Successfully";
            $response["data"] = $company;
        }

        return $response;

    }

    public function destroy($id){
        $company = Companies::find($id);

        if(empty($companies)){
            $response["success"] = false;
            $response["message"] = "No results found";
        } else {
            $company->delete();
            $response["success"] = true;
            $response["message"] = "Company Deleted Successfully";
            $response["data"] = $company;
        }

        return $response;
    }

    public function show($id){
        $company = Companies::where('id', $id)->with(
            'package',
            'companyDetail',
            'companyDetail.country',
            'companyDetail.language',
            'companyDetail.timezone',
            'companyDetail.currency'
        )->get();
        if($company->isEmpty()){
            $response["success"] = false;
            $response["message"] = "No results found";
        } else {
            $response["success"] = true;
            $response["message"] = "Results found";
            $response["data"] = $company;
        }

        return $response;
    }

    public function updateCompany($id, Request $request){
        $company = Companies::find($id);

        if(empty($company)){
            $response["success"] = false;
            $response["message"] = "No results found";
        } else {
            $company->name = $request->companyName;
            $company->website = $request->companyWebsite;
            $company->package_id = $request->defaultPackage;
            $company->status = $request->status;
            $company->save();
            $companyDetail = CompanyDetail::where('company_id', $id)->first();
            $companyDetail->address_line_1 = $request->companyAddress;
            $companyDetail->currency_id = $request->defaultCurrency;
            $companyDetail->timezone_id = $request->defaultTimezone;
            $companyDetail->language_id = $request->defaultLanguage;
            $companyDetail->email = $request->companyEmail;
            $companyDetail->phone = $request->companyPhone;
            $companyDetail->save();

            $response["success"] = true;
            $response["message"] = "Updated Successfully";
            $response["data"] = $company;
            $response["data"]["company_detail"] = $companyDetail;
        }

        return $response;
    }

    public function showCompany(){
        $user_id = auth('sanctum')->user()->id;
        $company = Companies::where('admin_id', $user_id)
        ->with('companyDetail',
        'companyDetail.country',
        'companyDetail.language',
        'companyDetail.timezone',
        'companyDetail.currency'
        )->get();
        if($company->isEmpty()){
            $response["success"] = false;
            $response["message"] = "No results found";
        } else {
            $response["success"] = true;
            $response["message"] = "Result found";
            $response["data"] = $company;
        }

        return $response;
    }

    private function uploadImage($image, $sub_dir)
    {
        //$image your base64 encoded
        $extension = '';
        if (stristr($image, "data:image/png")) $extension = 'png';
        else if (stristr($image, "data:image/jpg")) $extension = 'jpg';
        else $extension = 'jpeg';

        $image = str_replace("data:image/$extension;base64,", '', $image);
        $image = str_replace(' ', '+', $image);
        $filename = Str::random(8) . "_" . "_" . uniqid() . '.' . $extension;
        $path = public_path("images/$sub_dir") . '/' . $filename;

        \File::put($path, base64_decode($image));
        $db_path = 'images/' . $sub_dir . '/' . $filename;
        return $db_path;
    }
}