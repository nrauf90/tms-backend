<?php

namespace App\Repository\CompanyUserManagement;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Traits\ApiResponse;

class CompanyUser implements CompanyUserManagementInterface{
    use ApiResponse;

    public function index(Request $request){
        $company_id = Company::where('admin_id', auth('sanctum')->user()->id)->first()->id;

        if($request->role == null and $request->status == null and $request->name == null){

            $companyUsers = User::where('company_id', $company_id)
            ->with('roles')
            ->paginate($request->length);
            if($companyUsers->isEmpty()){
                return $this->errorResponse(getStatusCode('BASIC', 'NOT_FOUND'), getStatusCode('HTTP', 'SUCCESS'), 'NO RECORD FOUND');
            } else {
                return $this->successResponse($companyUsers, getStatusCode('BASIC', 'SUCCESS'), getStatusCode('HTTP', 'SUCCESS'), 'Company Users List', User::where('company_id', $company_id)->count());
            }
        } else {
            $companyUsers = User::where('company_id', $company_id)->where(function ($query) use ($request){
                if($request->name != null){
                    return $query->where('fname', 'LIKE', '%'.$request->name.'%');
                }
            })
            ->where(function ($query) use ($request){
                if($request->name != null){
                    return $query->where('lname', 'LIKE', '%'.$request->name.'%');
                }
            })
            ->where(function ($query) use ($request){
                if($request->status != null){
                    return $query->where('status', $request->status);
                }
            })
            ->whereHas('roles', function ($query) use ($request){
                if($request->role != null){
                    return $query->where('id', $request->role);
                }
            })
            ->paginate($request->length);

            if($companyUsers->isEmpty()){
                return $this->errorResponse(getStatusCode('BASIC', 'NOT_FOUND'), getStatusCode('HTTP', 'SUCCESS'), 'NO RECORD FOUND');
            } else {
                return $this->successResponse($companyUsers, getStatusCode('BASIC', 'SUCCESS'), getStatusCode('HTTP', 'SUCCESS'), 'Company Users List', User::where('company_id', $company_id)->count());
            }
        }
    }

    public function store(Request $request){
        $check = User::where('email', $request->email)->where('username', $request->userName)->get();
        if($check->isEmpty()){
            $company_id = Company::where('admin_id', auth('sanctum')->user()->id)->first()->id;
            $user = new User;
            $user->email = $request->email;
            $user->username = $request->firstname . $request->lastname;
            $user->fname = $request->firstname;
            $user->lname = $request->lastname;
            $user->password = Hash::make($request->password);
            $user->status = 1;
            $user->company_id = $company_id;
            $user->image = "images/users/user_placeholder.png";
            $user->save();
            foreach($request->roles as $role){
                $user->assignRole($role);
            }
            if(! $user){
                return $this->errorResponse(getStatusCode('OTHERS', 'RECORD_NOT_SAVED'), getStatusCode('HTTP', 'BAD_REQUEST'), 'Failed to save record');
            } else {
                return $this->successResponse($user, getStatusCode('BASIC', 'SUCCESS'), getStatusCode('HTTP', 'SUCCESS'), 'Data saved successfully');
            }
        } else {
            return $this->errorResponse(getStatusCode('OTHERS', 'USERNAME_ALREADY_EXISTS'), getStatusCode('HTTP', 'BAD_REQUEST'), 'Failed to save record');
        }
    }

    public function update($id, Request $request){
        $user = User::find($id);

        if(empty($user)){
            return $this->errorResponse(getStatusCode('OTHERS', 'USER_NOT_FOUND'), getStatusCode('HTTP', 'NOT_FOUND'), 'No user found');
        } else {
            $check = User::where('email', $request->email)->where('username', $request->userName)->get();
            if($check->isEmpty()){
            $user->email = $request->email;
            $user->username = $request->firstname . $request->lastname;
            $user->fname = $request->firstname;
            $user->lname = $request->lastname;
            $user->password = Hash::make($request->password);
            $user->status = 1;
            $user->image = "images/users/user_placeholder.png";
            $user->save();
            $user->syncRoles([]);
            foreach($request->roles as $role){
                $user->assignRole($role);
            }
            if(! $user){
                return $this->errorResponse(getStatusCode('OTHERS', 'RECORD_NOT_SAVED'), getStatusCode('HTTP', 'BAD_REQUEST'), 'Failed to save record');
            } else {
                return $this->successResponse($user, getStatusCode('BASIC', 'SUCCESS'), getStatusCode('HTTP', 'UPDATED'), 'Data saved successfully');
            }
        } else {
            return $this->errorResponse(getStatusCode('OTHERS', 'USERNAME_ALREADY_EXISTS'), getStatusCode('HTTP', 'BAD_REQUEST'), 'Failed to save record');
        }
    }
}

    public function destroy($id){
        $user = User::find($id);
        if(empty($user)){
            return $this->errorResponse(getStatusCode('OTHERS', 'USER_NOT_FOUND'), getStatusCode('HTTP', 'NOT_FOUND'), 'No user found');
        } else {
            $user->delete();
            return $this->successResponse($user, getStatusCode('BASIC', 'SUCCESS'), getStatusCode('HTTP', 'SUCCESS'), 'User deleted successfully');
        }
    }
    
     public function show($id){
        $user = User::find($id);
        if(empty($user)){
            return $this->errorResponse(getStatusCode('OTHERS', 'USER_NOT_FOUND'), getStatusCode('HTTP', 'NOT_FOUND'), 'No user found');
        } else {
            return $this->successResponse($user, getStatusCode('BASIC', 'SUCCESS'), getStatusCode('HTTP', 'SUCCESS'), 'User deleted successfully');
        }
    }

}