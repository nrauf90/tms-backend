<?php

namespace App\Repository\RolesPermissions\PermissionGroupManagement;

use Illuminate\Http\Request;

interface PermissionGroupManagementInterface{
    //discription: This method will be used to get the records of all the groups
    public function index(Request $request);

    //discription: This method will be used to add new group
    public function store(Request $request);

    //discription: This method will be used to edit the group
    //@param: id of the group which we want to update.
    public function update($id, Request $request);

    //discription: This method will be used to delete the group.
    public function destroy($id);
}