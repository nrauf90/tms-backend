<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Repository\RolesPermissions\RolesManagement\RolesManagementInterface;
use Spatie\Permission\Models\Role;
use App\Models\User;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Roles",
 *     description="API Endpoints for Role Management"
 * )
 */
class RolesController extends BaseController
{
    public $repositoryObj;

    public function __construct(RolesManagementInterface $roleObj)
    {
        $this->repositoryObj = $roleObj;
    }

    /**
     * @OA\Get(
     *     path="/api/roles",
     *     tags={"Roles"},
     *     summary="Get list of roles",
     *     description="Returns list of all roles with their permissions",
     *     security={{"apiAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array", @OA\Items(
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="permissions", type="array", @OA\Items(type="string"))
     *             )),
     *             @OA\Property(property="message", type="string", example="Roles retrieved successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No roles found"
     *     )
     * )
     */
    public function index(Request $request)
    {
        $response = $this->repositoryObj->index($request);
        if (!$response["success"]) {
            return $this->sendError('The Record Could not found.', ['error' => $response["message"]]);
        }
        return $this->sendResponse($response["data"], $response["message"]);
    }

    /**
     * @OA\Post(
     *     path="/api/roles",
     *     tags={"Roles"},
     *     summary="Create new role",
     *     description="Creates a new role with specified permissions",
     *     security={{"apiAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="editor"),
     *             @OA\Property(property="permissions", type="array", @OA\Items(type="string"), example={"edit_posts", "delete_posts"})
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Role created successfully"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $response = $this->repositoryObj->store($request);
        if (!$response["success"]) {
            return $this->sendError('The Record Could not Saved.', ['error' => $response["message"]]);
        }
        return $this->sendResponse($response["data"], $response["message"]);
    }

    /**
     * @OA\Get(
     *     path="/api/roles/{id}",
     *     tags={"Roles"},
     *     summary="Get role by ID",
     *     description="Returns role data with its permissions",
     *     security={{"apiAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of role to return",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Role not found"
     *     )
     * )
     */
    public function show($id)
    {
        $response = $this->repositoryObj->show($id);
        if (!$response["success"]) {
            return $this->sendError('The Record Could not found.', ['error' => $response["message"]]);
        }
        return $this->sendResponse($response["data"], $response["message"]);
    }

    /**
     * @OA\Put(
     *     path="/api/roles/{id}",
     *     tags={"Roles"},
     *     summary="Update role",
     *     description="Updates role data and permissions",
     *     security={{"apiAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of role to update",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="permissions", type="array", @OA\Items(type="string"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Role updated successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Role not found"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $response = $this->repositoryObj->update($id, $request);
        if (!$response["success"]) {
            return $this->sendError('The Record Could not be updated.', ['error' => $response["message"]]);
        }
        return $this->sendResponse($response["data"], $response["message"]);
    }

    /**
     * @OA\Delete(
     *     path="/api/roles/{id}",
     *     tags={"Roles"},
     *     summary="Delete role",
     *     description="Deletes a role and its associated permissions",
     *     security={{"apiAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of role to delete",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Role deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Role not found"
     *     )
     * )
     */
    public function destroy($id)
    {
        $response = $this->repositoryObj->destroy($id);
        if (!$response["success"]) {
            return $this->sendError('The Record Could not be deleted.', ['error' => $response["message"]]);
        }
        return $this->sendResponse($response["data"], $response["message"]);
    }

    /**
     * @OA\Post(
     *     path="/api/roles/{role}/users/{user}",
     *     tags={"Roles"},
     *     summary="Assign role to user",
     *     description="Assigns a role to a specific user",
     *     security={{"apiAuth":{}}},
     *     @OA\Parameter(
     *         name="role",
     *         in="path",
     *         description="ID of the role",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="user",
     *         in="path",
     *         description="ID of the user",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Role assigned successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Role or user not found"
     *     )
     * )
     */
    public function rolesAddUser($role, $user)
    {
        $response = $this->repositoryObj->rolesAddUser($role, $user);
        if (!$response["success"]) {
            return $this->sendError('The Record Could not found.', ['error' => $response["message"]]);
        }
        return $this->sendResponse($response["data"], $response["message"]);
    }

    /**
     * @OA\Delete(
     *     path="/api/roles/{role}/users/{user}",
     *     tags={"Roles"},
     *     summary="Remove role from user",
     *     description="Removes a role from a specific user",
     *     security={{"apiAuth":{}}},
     *     @OA\Parameter(
     *         name="role",
     *         in="path",
     *         description="Role model",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="user",
     *         in="path",
     *         description="User model",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Role removed successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Role or user not found"
     *     )
     * )
     */
    public function rolesRemoveUser(Role $role, User $user)
    {
        $response = $this->repositoryObj->rolesRemoveUser($role, $user);
        if (!$response["success"]) {
            return $this->sendError('The Record Could not found.', ['error' => $response["message"]]);
        }
        return $this->sendResponse($response["data"], $response["message"]);
    }

    /**
     * @OA\Get(
     *     path="/api/roles/{id}/permissions",
     *     tags={"Roles"},
     *     summary="Get role permissions",
     *     description="Returns all permissions assigned to a role",
     *     security={{"apiAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the role",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Role not found"
     *     )
     * )
     */
    public function rolePermissions($id)
    {
        $response = $this->repositoryObj->rolePermissions($id);
        if(!$response["success"]){
            return $this->sendError('Unable to find records.', ['error' => $response["message"]]);
        }
        return $this->sendResponse($response["data"], $response["message"]);
    }

    /**
     * @OA\Put(
     *     path="/api/roles/{id}/edit",
     *     tags={"Roles"},
     *     summary="Edit role details",
     *     description="Updates role details and permissions",
     *     security={{"apiAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of role to edit",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="permissions", type="array", @OA\Items(type="string"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Role updated successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Role not found"
     *     )
     * )
     */
    public function editRole($id, Request $request)
    {
        $response = $this->repositoryObj->editRole($id, $request);
        if(!$response["success"]){
            return $this->sendError('Unable to update the records.', ['error' => $response["message"]]);
        }
        return $this->sendResponse($response["data"], $response["message"]);
    }

    /**
     * @OA\Get(
     *     path="/api/roles/{id}/single",
     *     tags={"Roles"},
     *     summary="Get detailed role information",
     *     description="Returns detailed information about a specific role",
     *     security={{"apiAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of role to get details for",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             ),
     *             @OA\Property(property="message", type="string", example="Role details retrieved successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Role not found"
     *     )
     * )
     */
    public function showSingleRole($id)
    {
        $response = $this->repositoryObj->showSingleRole($id);
        if (!$response["success"]) {
            return $this->sendError('The Record Could not found.', ['error' => $response["message"]]);
        }
        return $this->sendResponse($response["data"], $response["message"]);
    }

    /**
     * @OA\Get(
     *     path="/api/roles/{id}/users",
     *     tags={"Roles"},
     *     summary="Get users with specific role",
     *     description="Returns list of users who have been assigned a specific role",
     *     security={{"apiAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of role to get users for",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer"),
     *                     @OA\Property(property="username", type="string"),
     *                     @OA\Property(property="fname", type="string"),
     *                     @OA\Property(property="lname", type="string"),
     *                     @OA\Property(property="email", type="string", format="email")
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="Users with role retrieved successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Role not found"
     *     )
     * )
     */
    public function showUserWithRole($id)
    {
        $response = $this->repositoryObj->showUserWithRole($id);
        if (!$response["success"]) {
            return $this->sendError('The Record Could not found.', ['error' => $response["message"]]);
        }
        return $this->sendResponse($response["data"], $response["message"]);
    }

    /**
     * @OA\Post(
     *     path="/api/roles/{rolename}/add",
     *     tags={"Roles"},
     *     summary="Create role with name",
     *     description="Creates a new role with the specified name and optional permissions",
     *     security={{"apiAuth":{}}},
     *     @OA\Parameter(
     *         name="rolename",
     *         in="path",
     *         description="Name of the role to create",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\JsonContent(
     *             @OA\Property(property="permissions", type="array", @OA\Items(type="string"),
     *                 description="Optional array of permission names to assign to the role"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Role created successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="permissions", type="array", @OA\Items(type="string"))
     *             ),
     *             @OA\Property(property="message", type="string", example="Role created successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input or role name already exists"
     *     )
     * )
     */
    public function addRole($rolename, Request $request)
    {
        $response = $this->repositoryObj->addRole($rolename, $request);
        if (!$response["success"]) {
            return $this->sendError('The Record Could not found.', ['error' => $response["message"]]);
        }
        return $this->sendResponse($response["data"], $response["message"]);
    }
}
