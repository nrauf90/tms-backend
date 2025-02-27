<?php

namespace App\Repository\UserManagement;

use Illuminate\Http\Request;

/**
 *
 */
interface UserManagementInterface
{

    // description: this function will show all Users
    public function index(Request $request);

    // description: this function will Add new User
    public function store(Request $request);


    // @param $id
    // description: this function will get specific User
    public function show($id);

    // @param $id
    // description: this function will update User
    public function update($id, Request $request);

    // @param $id
    // description: this function will delete User
    public function destroy($id);


    //description: this function will assign permission to a specific role
    public function assignPermissionToRole($role, $permission);



    //description: this function will revoke permission to a specific role
    public function removePermissionFromRole($role, $permission);




    //description: this function will create new permission
    public function createPermission($pname);




    //description: this function will assign permission to a specific user
    public function assignPermissionToUser($user, $permission);




    //description: this function will show assigned permission to a specific user
    public function showPermissionsAssignedToUser($id);



    //description: this function will revoke permission to a specific user
    public function revokePermissionFromUser($user, $permission);




    //description: this function will change password of a specific user
    public function changePassword($id, Request $request);

    //description: this function will be used to reset forgotten passwords. (Used to send link to email)
    public function sendresetPasswordLink(Request $request);


    public function resetPassword(Request $request);

    public function getSignedInUser(Request $request);
}
