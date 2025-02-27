<?php
namespace App\Repository\RolesPermissions\RolesManagement;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Spatie\Permission\Models\Role;
use App\Models\Roles as newRolesModel;
use App\Models\RoleHasPermissions;
use Illuminate\Support\Facades\DB;

/**
 * Class Roles
 * @package App\Repository\RolesManagement
 * Handle Operations Related to Roles
 */
class Roles implements RolesManagementInterface
{

    /**
     * @param Request $request
     * @return array
     *  Get All Roles
     */
    public function index(Request $request)
    {

    //     $columns = [ 'name', 'label', 'guard_name'];
    //     $length = $request->input('length') ?? 10;
    //     $column = $request->input('column') ?? "0";
    //     $dir = $request->input('dir') ?? "asc";
    //     $page = $request->input('page') ?? 1;
    //     $searchValue = $request->input('search');
    //     $query = Role::orderBy($columns[$column], $dir);
    //     if(!empty($request->input('exclude')))
    //     {
    //         $query->where('name', '!=',  $request->input('exclude') );
    //     }
    //     if (!empty($request->input('include'))) {
    //         $included_roles = explode(',', $request->input('include'));
    //         $query->whereIn('name',  $included_roles);
    //     }
    //     if (!empty($searchValue)) {
    //         $query->where(function ($query) use ($searchValue) {
    //             $query->Where('name', 'like', '%' . $searchValue . '%')
    //                 ->orWhere('guard_name', 'like', '%' . $searchValue . '%');
    //         });
    //     }   
    //     $withParams = ['permissions'];
        
    //     if ($request->input('permissionsConfigured'))
    //         $query->whereRaw('id IN (SELECT role_id FROM role_has_permissions)');
        
    //     $role = $query->with($withParams)->paginate($length, ['*'], 'page', $page);
        
    //     if (empty($role)) {
    //         $response["success"] = false;
    //         $response["message"] = "No results found";
    //     } else {
    //         $response["success"] = true;
    //         $response["message"] = "Results found Successfully";
    //         $response["data"] = $role;
    //     }
    //     return $response;
  
        $roles = newRolesModel::with('ModelHasRole.roleUser')->get();
        if($roles->isEmpty()){
            $response["success"] = false;
            $response["message"] = "No Results Found";
        } else {
            $response["success"] = true;
            $response["message"] = "Results found successfully";
            $response["data"] = $roles;
        }
        return $response;
    }

    /**
     * @param Request $request
     * @return array
     * Store role Data
     */
    public function store(Request $request)
    {
        $role = new Role;
        $label = explode(" ", $request->name);
        $newlabel = strtolower($label[0])."_".strtolower($label[1]);
        $role->name = $request->name;
        $role->label = $request->newlabel; 
        $role->guard_name = $request->guard_name;
        $role->save();

        if (!$role) {
            $response["success"] = false;
            $response["message"] = "Role Not Saved";
        } else {
            $response["success"] = true;
            $response["message"] = "Role has been Saved Successfully";
            $response["data"] = $role;
        }
        return $response;

    }

    /**
     * @param $id
     * @return array
     * Get Single role Detail
     */
    public function show($id)
    {
        $role = Role::find($id);
        if (empty($role)) {
            $response["success"] = false;
            $response["message"] = "No results found";
        } else {
            $response["success"] = true;
            $response["message"] = "Record Found";
            $role->users = $role->users;
            $response["data"] = $role;
        }
        return $response;
    }

    /**
     * @param $id
     * @param Request $request
     * @return array
     * Update role Data
     */
    public function update($id, Request $request)
    {
        $role = Role::find($id);

        if (empty($role)) {
            $response["success"] = false;
            $response["message"] = "No results found";
        } else {
            $role->name = $request->name  ?? $role->name;
            $role->label = $request->label  ?? $role->label;
            $role->guard_name = $request->guard_name  ?? $role->guard_name;
            $role->save();
            $response["success"] = true;
            $response["message"] = "Record Updated Successfully";
            $response["data"] = $role;
        }

        return $response;

    }

    /**
     * @param $id
     * @return array
     * Delete role Data
     */
    public function destroy($id)
    {
        $role = Role::find($id);
        if (empty($role)) {
            $response["success"] = false;
            $response["message"] = "No results found";
        } else {
            $role->delete();
            $response["success"] = true;
            $response["message"] = "Record Deleted Successfully";
            $response["data"] = $role;
        }

        return $response;
    }

    


 /**
     * @param $role, $user
     * @return array
     * Assign role to the user
     */
    public function rolesAddUser($role,$user)
    {
        $role=Role::find($role);
      
      if (empty($role)) {

            $response["success"] = false;
            $response["message"] = "No results found";
        } else {
            $user=User::find($user);
            $user->assignRole($role);
            $response["success"] = true;
            $response["message"] = "Role assigned Successfully";
            $response["data"]["role"] = $role;
            $response["data"]["user"] = $user;
        }
        return $response;
    }





 /**
     * @param $role, $user
     * @return array
     * Remove role from the user
     */
   public function rolesRemoveUser( $role, $user)
    {
        $role=Role::find($role)->first();
      
      if (empty($role)) {

            $response["success"] = false;
            $response["message"] = "No results found";
        } else {
            $user=User::find($user)->first();   
            $user->removeRole($role);
            $response["success"] = true;
            $response["message"] = "Role assigned Successfully";
            $response["data"]["role"] = $role;
            $response["data"]["user"] = $user;
        }
        return $response;
    }





 /**
     * @param $id
     * @return array
     * Show only role 
     */
    public function showSingleRole($id)
    {
        $role = Role::find($id);
        if (empty($role)) {
            $response["success"] = false;
            $response["message"] = "No results found";
        } else {
            $response["success"] = true;
            $response["message"] = "Record Found";
            $response["data"] = $role;
        }
        return $response;
    }






 /**
     * @param $id
     * @return array
     * Show user alongwith assigned role 
     */
    public function showUserWithRole($id)
    {
        $user = User::find($id);
        if (empty($user)) {
            $response["success"] = false;
            $response["message"] = "No results found";
        } else {
            $response["success"] = true;
            $response["message"] = "Record Found";
            $user->roles = $user->roles;
            $response["data"] = $user;
        }
        return $response;
    }







 /**
     * @param $rolename
     * @return array
     * Add role in the database 
     */
    public function addRole($rolename, Request $request)
    {
        //$role = Role::firstOrCreate(['name' => $rolename]);
        $role = new Role;
        $decodeLabel = urldecode($rolename);
        $label = explode(" ", $decodeLabel);
        $newlabel = strtolower($label[0])."_".strtolower($label[1]);
        $role->name = $rolename;
        $role->label = $newlabel; 
        $role->guard_name = config('auth.defaults.guard');
        $role->save();

        if (!$role) {
            $response["success"] = false;
            $response["message"] = "Role Not Saved";
        } else {
            if($request->has('permissions')){
            for($i=0; $i<count($request->permissions); $i++) {
                $rolePermission = new RoleHasPermissions;
                $rolePermission->permission_id = $request->permissions[$i];
                $rolePermission->role_id = $role->id;
                $rolePermission->save();
            }
            $response["success"] = true;
            $response["message"] = "Role has been Saved, And Permissions Assigned Successfully";
            $response["data"] = $role;
        } else {
            $response["success"] = true;
            $response["message"] = "Role has been Saved Successfully";
            $response["data"] = $role;
        }
        }
        return $response;
    }

    public function rolePermissions($id){
        $rolePermission=newRolesModel::where('id', $id)->with('roleHasPermissions.rolesPermissions')->get();
        if($rolePermission->isEmpty()){
            $response["success"] = false;
            $response["message"] = "Role or Permission not found.";
        }
        else{
            $response["success"] = true;
            $response["message"] = "Record Found !";
            $response["data"] = $rolePermission;
        }
        return $response;
        //dd($rolePermission);
    }

    public function editRole($id, Request $request){
        $role = Role::find($id);
        $role->name = $request->roleName ?? $role->name;
        $decodeLabel = urldecode($request->roleName);
        $label = explode(" ", $decodeLabel);
        $newlabel = strtolower($label[0])."_".strtolower($label[1]);
        $role->label = $newlabel;
        $role->save();
        //$roleName = newRolesModel::where('id', $id)->first();
        // First we will delete all the permissions of the role
        $rolePermissions = RoleHasPermissions::where('role_id', $id)->delete();
        $roleHasPermissions = "";
        // DB::transaction(function () use($request, $id) {
        //     for($i=0; $i<count($request->permissions); $i++){
        //         $roleHasPermissions = new RoleHasPermissions;
        //         $roleHasPermissions->permission_id = $request->permissions[$i];
        //         $roleHasPermissions->role_id = $id;
        //         $roleHasPermissions->save();
        //     }
        // });
        for($i=0; $i<count($request->permissions); $i++){
            $check = RoleHasPermissions::where('role_id', $id)->where('permission_id', $request->permissions[$i])->first();
            if(empty($check)){
                $roleHasPermissions = new RoleHasPermissions;
                $roleHasPermissions->role_id = $id;
                $roleHasPermissions->permission_id = $request->permissions[$i];
                $roleHasPermissions->save();
            }
        }
        if(! $roleHasPermissions){
            $response["success"] = false;
            $response["message"] = "Cannot Edit Role";
        }
        else{
            $response["success"] = true;
            $response["message"] = "Role Edit Successful";
            $response["data"] = $roleHasPermissions;
        }
        return $response;
    }
}
