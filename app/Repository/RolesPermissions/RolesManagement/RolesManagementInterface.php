<?php

namespace App\Repository\RolesPermissions\RolesManagement;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
/**
 *
 */
interface RolesManagementInterface
{

// description: this function will show all Roles
    public function index(Request $request);




// description: this function will Add new Role
    public function store(Request $request);



// @param $id
// description: this function will get specific Role
    public function show($id);




// @param $id
// description: this function will update Role
    public function update($id, Request $request);




// @param $id
// description: this function will delete Role
    public function destroy($id);




 //@param $role, $user   
// description: this function will assign role to the user
    public function rolesAddUser($role, $user);



//@param $role, $user   
// description: this function will remove role to the user
    public function rolesRemoveUser($role, $user);



//@param $id 
// description: this function will show single role 
    public function showSingleRole($id);



    //@param $id   
// description: this function will role of single user
    public function showUserWithRole($id);



//@param $rolename   
// description: this function will add role in the database, and assign permissions to the role.
// Assign Permission is the new functionality in this function for new MUI vuexy theme.
    public function addRole($rolename, Request $request);

//@param role_id
//description: this function will get the permissions aganist a specific role
    public function rolePermissions($id);

//description: this function will be used to edit the role and its associated permissions.
    public function editRole($id, Request $request);
    
}
