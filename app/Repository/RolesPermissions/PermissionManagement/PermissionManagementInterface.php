<?php

namespace App\Repository\RolesPermissions\PermissionManagement;

use Illuminate\Http\Request;


interface PermissionManagementInterface
{
    //description: this function will get all the permissions
    public function index(Request $request);

    //description: this function will delete a permission
    public function destroy($id);
    
    //description: this function will create new permission
    //Request object will be used to fetch the group name.
    public function createPermission($pname, Request $request);

    //description: this function will assign permission to a specific user {old function}
    public function assignPermissionToUser($user, $permission);

    //description: this function will be used to assign permissions to a specific role.
    public function assignPermissionToRole($roleId, Request $request);

    //description: this function will show assigned permission to a specific user
    public function showPermissionsAssignedToUser($id);

    //description: this function will revoke permission to a specific user
    public function revokePermissionFromUser($user, $permission);

    //discription: this function will create permission group
    public function createPermissionGroup(Request $request);

    //discription: this function will delete permission group and its associated permissions
    public function deletePermissionGroup($id);

    //discription: this function will return all the groups listed in the system
    public function getGroups();  

    //discription: this function will be used to edit permissions
    public function editPermission($permissionId, Request $request);

    //discription: this function will be used to add generic permissions like read,write,create in the system.
    public function createGenericPermissions(Request $request);

    //discription: this function will be used to get all the generic permissions listed in the system.
    public function getGenericPermissions();

    //discription: this function will be used to get the grouped permissions.
    public function getGroupPermissions();

    //discription: this function will be used to get data for the permission, which is then used to preload
    //data for the permission when we are editing it.
    public function getPermission($id);

}
