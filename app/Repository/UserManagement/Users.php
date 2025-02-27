<?php

namespace App\Repository\UserManagement;

use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\Input;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Password;
use App\Models\ModelHasRole;

/**
 * Class Users
 * @package App\Repository\UserManagement
 * Handle Operations Related to Users
 */
class Users implements UserManagementInterface
{
    /**
     * @param Request $request
     * @return array
     *  Get All Users
     */
    public function index(Request $request)
    {
        if($request->status == null and $request->role == null and $request->name == null) {

            $tableColumns = [
                'username|sort', 'status|sort', 'fname|sort', 'lname|sort', 'email|sort', 'dob|sort',  'mobile_no|sort', 'image',
                'created_at|sort', 'id'
            ];
            $sortColumns = [];
    
            foreach ($tableColumns as $key => $val) {
                $tempColumn = explode('|', $val);
                if (!empty($tempColumn[1])) {
                    if ($tempColumn[1] == "sort") {
                        $sortColumns[] = $tempColumn[0];
                    }
                }
            }
            $tableSelectedColumns = [];
            foreach ($tableColumns as $key => $val) {
                $tempColumn = explode('|', $val);
                $tableSelectedColumns[] = $tempColumn[0];
            }
    
            $length = $request->input('length') ?? 10;
            $columns = $request->input('columns') ?? '*';
            $column = $request->input('column') ?? "0";
            $dir = $request->input('dir') ?? "asc";
            $page = $request->input('page') ?? 1;
            $searchValue = $request->input('search');
            $selectedColumns = [];
            if ($columns != "*") {
                $columns = explode(',', $columns);
                foreach ($columns as $val) {
                    $selectedColumns[] = $tableSelectedColumns[$val];
                }
            } else {
                $selectedColumns = $tableSelectedColumns;
            }
    
            $query = User::select($selectedColumns)->orderBy($sortColumns[$column], $dir);
    
            if (!empty($searchValue)) {
                $query->where(function ($query) use ($searchValue) {
                    $query->where('username', 'like', '%' . $searchValue . '%')
                        ->orWhere('fname', 'like', '%' . $searchValue . '%')
                        ->orWhere('lname', 'like', '%' . $searchValue . '%')
                        ->orWhere('email', 'like', '%' . $searchValue . '%')
                        ->orWhere('detail', 'like', '%' . $searchValue . '%')
                        ->orWhereRaw("city_id IN (SELECT id FROM city WHERE name LIKE '%$searchValue%' )")
                        ->orWhereRaw("state_id IN (SELECT id FROM state WHERE name LIKE '%$searchValue%' )")
                        ->orWhereRaw("country_id IN (SELECT id FROM country WHERE name LIKE '%$searchValue%' )")
                        ->orWhere('street_address', 'like', '%' . $searchValue . '%')
                        ->orWhere('zip_code', 'like', '%' . $searchValue . '%')
                        ->orWhere('created_at', 'like', '%' . $searchValue . '%');
                });
            }
    
    
    
            $withParams = [];
            if (in_array('city_id', $selectedColumns)) {
                $withParams[] = 'city';
            }
    
            if (in_array('state_id', $selectedColumns)) {
                $withParams[] = 'state';
            }
    
            if (in_array('country_id', $selectedColumns)) {
                $withParams[] = 'country';
            }
    
            $withParams[] = 'roles';
    
            $users = $query->with($withParams)->paginate($length, ['*'], 'page', $page);
    
            if (empty($users)) {
                $response["success"] = false;
                $response["message"] = "No results found";
            } else {
                $response["success"] = true;
                $response["message"] = "Results found Successfully";
                $response["data"] = $users;
            }
        }
        else {
            $status = $request->status;
            $role = $request->role;
            $name = $request->name;
            $users = User::where(function ($query) use ($role) {
                if($role != null) {
                    return $query->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id');
                    
                }
            })
            ->where(function ($query) use ($status) {
                    if($status != null) {
                        return $query->where('status', $status);
                    }
                })
            ->where(function ($query) use ($name) {
                if($name != null) {
                    return $query->where('fname', 'LIKE', '%'.$name.'%');
                }
            })
            ->with('roles')->paginate(10);
            if(empty($users)){
                $response["success"] = false;
                $response["message"] = "No Results Found";
            }
            else {
                $response["success"] = true;
                $response["message"] = "Results found successfully";
                $response["data"] = $users;
            }

        }
        return $response;
        
    }


    public function store(Request $request)
    {
        $user = new User;
        $user->username = $request->data['email'];
        $user->fname = $request->data['firstName'];
        $user->lname = $request->data['lastName'];
        $user->email = $request->data['email'];
        //$user->dob = Carbon::parse($request->dob)->format('Y-m-d');
        $user->password = bcrypt($request->data['password']);

        if (preg_match("/^data:image\/([a-zA-Z]*);base64,([^\"]*)/", $request->image)) {
            $user->image = $this->uploadImage($request->image, 'users'); //returns the path of image
        } else {
            $user->image = "images/users/user_placeholder.png";
        }
        //$user->mobile_no = $request->data['contact'];
        $user->save();
        $role_id = $request->data['role'];
        $role = Role::find($role_id);
        $user->assignRole($role);


        if (!$user) {
            $response["success"] = false;
            $response["message"] = "Data Not Saved";
        } else {
            $response["success"] = true;
            $response["message"] = "Data has been Saved Successfully";
            $response["data"] = $user;
        }
        return $response;
    }

    /**
     * @param $id
     * @return array
     * Get Single User Detail
     */
    public function show($id)
    {
        $user = User::where('id', $id)->with(['roles'])->first();
        if (empty($user)) {
            $response["success"] = false;
            $response["message"] = "No results found";
        } else {
            $response["success"] = true;
            $response["message"] = "Record Found";
            $response["data"] = $user;
        }
        return $response;
    }

    /**
     * @param $id
     * @param Request $request
     * @return array
     * Update User Data
     */
    public function update($id, Request $request)
    {
        $user = User::find($id);

        if (empty($user)) {
            $response["success"] = false;
            $response["message"] = "No results found";
        } else {
            $user->username = $request->username ?? $user->username;
            $user->fname = $request->fname ?? $user->fname;
            $user->lname = $request->lname ?? $user->lname;
            $user->email = $request->email ?? $user->email;
            $user->dob = $request->dob ?? $user->dob;
            $user->status = $request->status ?? $user->status;
            $user->mobile_no = $request->mobile_no ?? $user->mobile_no;
            $user->password = $request->password;
            if (preg_match("/^data:image\/([a-zA-Z]*);base64,([^\"]*)/", $request->image)) {
                $user->image = $this->uploadImage($request->image, 'users'); //returns the path of image
            }

            $user->save();
            $response["success"] = true;
            $response["message"] = "Record Updated Successfully";
            $response["data"] = $user;
        }
        return $response;
    }

    /**
     * @param $id
     * @return array
     * Delete User Data
     */
    public function destroy($id)
    {
        $user = User::find($id);

        if (empty($user)) {
            $response["success"] = false;
            $response["message"] = "No results found";
        } else if ($user->hasRole('super_admin')) {
            $response["success"] = false;
            $response["message"] = "Cannot delete Super Admin";
        } else {
            $user->delete();
            $response["success"] = true;
            $response["message"] = "Record Deleted Successfully";
            $response["data"] = $user;
        }
        return $response;
    }

    /**
     * @param $pname
     * @return array
     * Create Permissions in the database
     */
    public function createPermission($pname)
    {
        $permission = Permission::create(['name' => $pname]);
        if (empty($permission)) {
            $response["success"] = false;
            $response["message"] = "No Permission Created";
        } else {
            $response["success"] = true;
            $response["message"] = "Permission Created Successfully";
            $response["data"]["permission"] = $permission;
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
        $user = User::find($user);

        if (empty($user)) {
            $response["success"] = false;
            $response["message"] = "No results found";
        } else {
            $permission = Permission::find($permission);
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
        $user = User::find($user);

        if (empty($user)) {
            $response["success"] = false;
            $response["message"] = "No results found";
        } else {
            $permission = Permission::find($permission);
            $user->revokePermissionTo($permission);
            $response["success"] = true;
            $response["message"] = "Permission revoked Successfully";
            $response["data"]["user"] = $user;
            $response["data"]["permission"] = $permission;
        }
        return $response;
    }




    /**
     * @param $role, $permission
     * @return array
     * Assign Permissions to the role
     */
    public function assignPermissionToRole($role, $permission)
    {
        $role = Role::find($role);

        if (empty($role)) {
            $response["success"] = false;
            $response["message"] = "No results found";
        } else {
            $permission = Permission::find($permission);
            $role->givePermissionTo($permission);
            $response["success"] = true;
            $response["message"] = "Record Updated Successfully";
            $response["data"]["role"] = $role;
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
        $user = User::find($id);
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





    /**
     * @param $role, $permission
     * @return array
     * Remove Permissions to the user
     */
    public function removePermissionFromRole($role, $permission)
    {

        $role = Role::find($role);

        if (empty($role)) {
            $response["success"] = false;
            $response["message"] = "No results found";
        } else {
            $permission = Permission::find($permission);
            $role->revokePermissionTo($permission);
            $response["success"] = true;
            $response["message"] = "Permission revoked Successfully";
            $response["data"]["user"] = $role;
            $response["data"]["permission"] = $permission;
        }
        return $response;
    }


    public function changePassword($id, Request $request)
    {
        $response["code"] = 404;
        $user = User::find($id);
        if (empty($user)) {
            $response["success"] = false;
            $response["message"] = "No results found";
            $response["code"] = 200;
        } else {
            $user = User::find($id);
            if (Hash::check($request->password, $user->password)) {
                $response["code"] = 200;
                if (empty($request->newpassword)) {
                    $response["success"] = false;
                    $response["message"] = "password empty";
                    $response["code"] = 200;
                } else {
                    $user->password = Hash::make($request->newpassword);
                    $user->save();
                    $response["success"] = true;
                    $response["message"] = "Password changed Successfully";
                    $response["data"] = $user;
                    $response["code"] = 200;
                }
            } else {
                $response["success"] = false;
                $response["message"] = "password is incorrect";
                $response["code"] = 200;
            }
            return $response;
        }
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

    /**
     * @param $id
     * @return array
     * View Permissions assigned to the role
     */
    public function viewAssignPermissionToRole()
    {
        $roles = Role::with('permissions')->get();

        if ($roles->count()< 0) {
            $response["success"] = false;
            $response["message"] = "No results found";
        } else {
            $response["success"] = true;
            $response["message"] = "Record Found";
            $response["data"] = $roles;
        }
        return $response;
    }

    /*
    For resetting password link
    */
    public function sendresetPasswordLink(Request $request){
        $request->validate([
            'email' => 'required|email'
        ]);
        //REFER DOCUMENTATION FOR FURTHER PROCESS OF CONFIGURING MAIL TRAP.
    }

    public function resetPassword(Request $request){
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);
        //REFER DOCUMENTATION FOR FURTHER PROCESS OF CONFIGURING MAIL TRAP.
    }

    public function getSignedInUser(Request $request){
        $user = auth('sanctum')->user();

        if(empty('$user')){
            $response["success"] = false;
            $response["message"] = "Unable to fetch user";
        }
        else {
            $response["success"] = true;
            $response['id'] = $user->id;
            $response['username'] = $user->username;
            $response['fullName'] = $user->getFullName();
            $response['email'] = $user->email;
            $response['avatar'] = $user->image ;
            $response['role'] = $user->roles;
            $permissions = $user->getAbility();
            $response['ability'] = $permissions;
            $response["accessToken"] = $request->bearerToken();
            $response["refreshToken"] = $request->bearerToken();
        }
        return $response;
    }
}
