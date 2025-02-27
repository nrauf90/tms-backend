<?php

namespace App\Repository\RolesPermissions\PermissionGroupManagement;

use Illuminate\Http\Request;
use App\Models\PermissionGroup as PermissionsGroup;

class PermissionGroup implements PermissionGroupManagementInterface{

    public function index(Request $request){
        if($request->groupName == null){
            $groups = PermissionsGroup::paginate($request->length);

            if($groups->isEmpty()){
                $response["success"] = false;
                $response["message"] = "No results found";
            } else {
                $response["success"] = true;
                $response["message"] = "Results found successfully";
                $response["data"] = $groups;
            }
        } else {
            $groupName = $request->groupName;
            $groups = PermissionsGroup::where(function ($query) use ($groupName){
                return $query->where('group_name', 'LIKE', '%'.$groupName.'%');
            })
            ->paginate($request->length);

            if(empty($groups)){
                $response["success"] = false;
                $response["message"] = "No Results found";
            } else {
                $response["success"] = true;
                $response["message"] = "Results found successfully";
                $response["data"] = $groups;
            }
        }

        return $response;
    }

    public function store(Request $request){
        $check = PermissionsGroup::where('group_name', $request->groupName)->get();

        if($check->isEmpty()){
            $group = new PermissionsGroup;
            $group->group_name = $request->groupName;
            $group->save();

            if(! $group){
                $response["success"] = false;
                $response["message"] = "Data not saved";
            } else {
                $response["success"] = true;
                $response["message"] = "Group has been created successfully";
                $response["data"] = $group;
            }
        } else {
            $response["success"] = false;
            $response["message"] = "This group already exists";
        }

        return $response;
    }

    public function update($id, Request $request){
        $group = PermissionsGroup::find($id);

        if(empty($group)){
            $response["success"] = false;
            $response["message"] = "No results found";
        } else {
            $group->group_name = $request->groupName;
            $group->save();
            
            if(! $group){
                $response["success"] = false;
                $response["message"] = "Unable to update group";
            } else {
                $response["success"] = true;
                $response["message"] = "Group updated successfully";
                $response["data"] = $group;
            }
        }

        return $response;
    }

    public function destroy($id){
        $group = PermissionsGroup::find($id);

        if(empty($group)){
            $response["success"] = false;
            $response["message"] = "No results found";
        } else {
            $group->delete();
            $response["success"] = true;
            $response["message"] = "Group deleted successfully";
            $response["data"] = $group;
        }

        return $response;
    }
}