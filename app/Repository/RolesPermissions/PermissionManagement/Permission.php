<?php

namespace App\Repository\RolesPermissions\PermissionManagement;

use Illuminate\Http\Request;
use App\Models\Permission as Permissions;
use Spatie\Permission\Models\Permission as ModelPermission;
use App\Models\PermissionGroup;
use App\Models\GroupPermission;
use App\Models\AssignPermissionsToRole;
use App\Models\GenericPermission;
use App\Events\Notify;
//use App\Models\Notification;

/**
 * Class Permissions
 * @package App\Repository\UserManagement
 * Handle Operations Related to Permissions
 */
class Permission implements PermissionManagementInterface
{
    /**
     * @param Request $request
     * @return array
     *  Get All Permissions
     */
    public function index(Request $request)
    {
        if($request->permissionName == null){
            $permissions = Permissions::paginate($request->length);

            if($permissions->isEmpty()){
                $response["success"] = false;
                $response["message"] = "No Results found";
            } else {
                $response["success"] = true;
                $response["message"] = "Results found successfully";
                $response["data"] = $permissions;
            }
        } else {
            $permissions = Permissions::where(function ($query) use ($request){
                return $query->where('name', 'LIKE', '%'.$request->permissionName.'%');
            })->paginate($request->length);

            if($permissions->isEmpty()){
                $response["success"] = false;
                $response["message"] = "No results found";
            } else {
                // // $newNotification = new Notification();
                // // $newNotification->type="Test Notification";
                // // $newNotification->description="Test Description";
                // // $newNotification->receiver_id = 1;
                // // $newNotification->status=0;
                // // $newNotification->save();
                // event(new Notify(1));
                $response["success"] = true;
                $response["message"] = "Results found successfully";
                $response["data"] = $permissions;
            }
        }

        return $response;
    }


    /**
     * @param $id
     * @return array
     * Delete Permission
     */
    public function destroy($id)
    {
        $permission = ModelPermission::find($id);

        if (empty($permission)) {
            $response["success"] = false;
            $response["message"] = "No results found";
        } else {
            $permission->delete();
            $response["success"] = true;
            $response["message"] = "Record Deleted Successfully";
            $response["data"] = $permission;
        }
        return $response;
    }


    /**
     * @param $pname
     * @return array
     * Create Permissions in the database
     */
    public function createPermission($permission, Request $request)
    {
        //FIRST WE WILL CHECK THAT WHETHER THE PERMISSION & SLUG WITH THE SAME NAME EXISTS OR NOT?.
        $permissionName = strtolower($request->permission) . "-" . $request->slug;
        $checkPermission = Permissions::where('name', $permissionName)->first();
        if(empty($checkPermission)) {
            //CHECKING PERMISSION ONLY HERE
            $groupPermissions = GroupPermission::where('group_id', PermissionGroup::where('group_name', $request->group_name)->first()->id)->with('permissions')->get();
            foreach($groupPermissions as $groupPermission){
                $permissionSlugArr = explode("-", $groupPermission->permissions->name);
                if($permissionSlugArr[0] == $request->permission) {
                    $response["success"] = false;
                    $response["message"] = "This permission already exists in this group";
                    return $response;
                }
            }
        $permission = ModelPermission::create(['name' => $permissionName]);
        if (empty($permission)) {
            $response["success"] = false;
            $response["message"] = "No Permission Created";
        } else {
            $group_permission = new GroupPermission;
            $group_permission->permission_id = $permission->id;
            $group_id = PermissionGroup::where('group_name', $request->group_name)->first()->id;
            $group_permission->group_id = $group_id;
            $group_permission->save();
            $group_name = PermissionGroup::where('id', $group_id)->first();
            $response["success"] = true;
            $response["message"] = "Permission Created Successfully";
            $response["data"]["permission"] = $permission;
            $response["data"]["group"] = $group_name;
        }
        } else {
            $checkGroup = 
            GroupPermission::where('group_id', PermissionGroup::where('group_name', $request->group_name)->first()->id)
            ->where('permission_id', $checkPermission->id)->get();
            if($checkGroup->isEmpty()) {
                $group_permission = new GroupPermission;
                $group_permission->permission_id = $checkPermission->id;
                $group_permission->group_id = PermissionGroup::where('id', $group_id)->first()->id;
                $group_permission->save();
                $response["success"] = true;
                $response["message"] = "Permission Already Exists ! And Now Added Into Group !";
                $response["data"]["permission"] = $checkPermission;
                $response["data"]["group"] = $group_name; 
            }
            else {
                $flag = 0;
                $groupPermissions = GroupPermission::where('group_id', PermissionGroup::where('group_name', $request->group_name)->first()->id)->with('permissions')->get();
                foreach($groupPermissions as $groupPermission) {
                        $permissionSlugArr = explode("-", $groupPermission->permissions->name);
                        if($permissionSlugArr[0] == $request->permission) {
                            $response["success"] = false;
                            $response["message"] = "This permission already exists in this group";
                            return $response;
                        }
                }
            }
        }
        return $response;
    }

    /**
     * @param $user, $permission
     * @return array
     * Assign Permissions to the user
     */
    public function assignPermissionToUser($user, $permission)
    {
        $user = ModelPermission::find($user);

        if (empty($user)) {
            $response["success"] = false;
            $response["message"] = "No results found";
        } else {
            $permission = ModelPermission::find($permission);
            $user->givePermissionTo($permission);
            $response["success"] = true;
            $response["message"] = "Permission assigned Successfully";
            $response["data"]["user"] = $user;
            $response["data"]["permission"] = $permission;
        }
        return $response;
    }




    /**
     * @param $user, $permission
     * @return array
     * Remove Permissions to the user
     */
    public function revokePermissionFromUser($user, $permission)
    {
        $user = ModelPermission::find($user);

        if (empty($user)) {
            $response["success"] = false;
            $response["message"] = "No results found";
        } else {
            $permission = ModelPermission::find($permission);
            $user->revokePermissionTo($permission);
            $response["success"] = true;
            $response["message"] = "Permission revoked Successfully";
            $response["data"]["user"] = $user;
            $response["data"]["permission"] = $permission;
        }
        return $response;
    }

    /**
     * @param $id
     * @return array
     * Show Permissions assigned to the user
     */
    public function showPermissionsAssignedToUser($id)
    {
        $user = ModelPermission::find($id);
        if (empty($user)) {
            $response["success"] = false;
            $response["message"] = "No results found";
        } else {
            $response["success"] = true;
            $response["message"] = "Record Found";
            $permissionNames = $user->getPermissionNames();
            $response["data"] = $permissionNames;
            $response["data"] = $user;
        }
        return $response;
    }

    public function createPermissionGroup(Request $request){

        $permission_group=new PermissionGroup;
        $permission_group->group_name=$request->group_name;
        $permission_group->save();

        if(!$permission_group){
            $response["success"] = false;
            $response["message"] = "Data Not Saved";
        }
        else{
            $response["success"] = true;
            $response["message"] = "Data has been saved successfully";
            $response["data"] = $permission_group;
        }
        return $response;

    }

    public function deletePermissionGroup($id){
        $permission_group=PermissionGroup::find($id);

        if(empty($permission_group)){
            $response["success"] = false;
            $response["message"] = "No results found";
        }
        else{
            $permission_group->delete();
            $response["success"] = true;
            $response["message"] = "Record Deleted Successfully";
            $response["data"] = $permission_group;
        }
        return $response;
    }

    public function getGroups(){
        $permission_groups=PermissionGroup::all();

        if($permission_groups->isEmpty()){
            $response["success"] = false;
            $response["message"] = "No groups found";
        }
        else{
            $response["success"] = true;
            $response["message"] = "Records Found !";
            $response["data"] = $permission_groups;
        }
        return $response;
    }

    public function assignPermissionToRole($roleId, Request $request){

        $permissionsCount=count($request->checkedPermission);
        for($x=0;$x<$permissionsCount;$x++){
            $exists=AssignPermissionsToRole::where('role_id', $roleId)->where('permission_id', $request->checkedPermission[$x]['id'])->get();
            if($exists->isEmpty()){
            $newPermissions= new AssignPermissionsToRole;
            $newPermissions->role_id=$roleId;
            $newPermissions->permission_id=$request->checkedPermission[$x]['id'];
            $newPermissions->save();
            }
        }
        if(!$newPermissions){
            $response["success"] = false;
            $response["message"] = "Data Not Saved !";
        }
        else{
            $response["success"] = true;
            $response["message"] = "Data has been saved successfully";
            $response["data"] = $newPermissions;
        }
        return $response;
    }

    public function editPermission($permissionId, Request $request){
        $permission=Permissions::find($permissionId);
        if(empty($permission)){
            $response["success"] = false;
            $response["message"] = "Unable to find permission !";
        }else {
            $newPermission = strtolower($request->permission) . "-" . $request->slug;
            $group_id = PermissionGroup::where('group_name', $request->group_name)->first()->id;
            $check = GroupPermission::where('group_id', $group_id)->where('permission_id', $permissionId)->first();
            if(empty($check)){
                $newGroupedPermission = new GroupPermission;
                $newGroupedPermission->permission_id = $permissionId;
                $newGroupedPermission->group_id = $group_id;
                $newGroupedPermission->save();
            } else {
                $check->group_id = $group_id;
                $check->save();
            }
            $permission->name = $newPermission;
            $permission->save();
            $response["success"] = true;
            $response["message"] = "Permission Updated Successfully !";
            $response["data"] = $permission;
        }
        return $response;
    }

    public function createGenericPermissions(Request $request){
        // FOLLOWING STEP IS TO CHECK THAT WHETHER THE PERMISSION ALREADY EXISTS OR NOT.
        $check = GenericPermission::where('name', $request->permission_name)->get();
        if($check->isEmpty()){
            $newPermission = new GenericPermission;
            $newPermission->name = $request->permission_name;
            $newPermission->save();

            if(! $newPermission){
                $response["success"] = false;
                $response["message"] = 'Permission Cannot Be Created';
            }
            else {
                $response["success"] = true;
                $response["message"] = 'Permission Created Successfully';
                $response["data"] = $newPermission;
            }
        }
        else {
            $response["success"] = false;
            $response["message"] = 'Permission Already Exist !';
        }
        return $response;
    }

    public function getGenericPermissions() {
        $genericPermissions = GenericPermission::all();
        if($genericPermissions->isEmpty()){
            $response["success"] = false;
            $response["message"] = 'No Permissions Found';
        }
        else {
            $response["success"] = true;
            $response["message"] = 'Data Found Successfully';
            $response["data"] = $genericPermissions;
        }
        return $response;
    }

    public function getGroupPermissions(){
        $groupedPermissions = PermissionGroup::with('groupedPermissions.permissions')->get();
        if($groupedPermissions->isEmpty()){
            $response["success"] = false;
            $response["message"] = 'No Results Found !';
        } else {
            $response["success"] = true;
            $response["message"] = 'Results Found';
            $response["data"] =  $groupedPermissions;
        }
        return $response;
    }

    public function getPermission($id){
        $permission = Permissions::where('id', $id)->with('groups.permissionGroup')->get();
        
        if(empty($permission)){
            $response["success"] = false;
            $response["message"] = "No Results Found";
        } else {
            $response["success"] = true;
            $response["message"] = "Results Found";
            $response["data"] = $permission;
        }
        
        return $response;
    }
}
