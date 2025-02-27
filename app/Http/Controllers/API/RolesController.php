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
     *     path="/api/user/role",
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
     *     path="/api/role/{role}",
     *     tags={"Roles"},
     *     summary="Add new role",
     *     description="Creates a new role with specified name",
     *     @OA\Parameter(
     *         name="role",
     *         in="path",
     *         description="Name of the role to create",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
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
    public function addRole($rolename, Request $request)
    {
        $response = $this->repositoryObj->addRole($rolename, $request);
        if (!$response["success"]) {
            return $this->sendError('The Record Could not found.', ['error' => $response["message"]]);
        }
        return $this->sendResponse($response["data"], $response["message"]);
    }

    /**
     * @OA\Get(
     *     path="/api/role/{role}",
     *     tags={"Roles"},
     *     summary="Get role details",
     *     description="Returns role data with its permissions",
     *     @OA\Parameter(
     *         name="role",
     *         in="path",
     *         description="Name of the role",
     *         required=true,
     *         @OA\Schema(type="string")
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
    public function showSingleRole($role)
    {
        $response = $this->repositoryObj->showSingleRole($role);
        if (!$response["success"]) {
            return $this->sendError('The Record Could not found.', ['error' => $response["message"]]);
        }
        return $this->sendResponse($response["data"], $response["message"]);
    }

    /**
     * @OA\Post(
     *     path="/api/user/role/{id}/edit",
     *     tags={"Roles"},
     *     summary="Edit role",
     *     description="Updates role data and permissions",
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
     *     path="/api/role/{id}/permissions",
     *     tags={"Roles"},
     *     summary="Get role permissions",
     *     description="Returns permissions assigned to a role",
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
     * @OA\Get(
     *     path="/api/user/{user}/role",
     *     tags={"Roles"},
     *     summary="Get user roles",
     *     description="Returns roles assigned to a user",
     *     @OA\Parameter(
     *         name="user",
     *         in="path",
     *         description="ID of the user",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found"
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
     *     path="/api/role/{role}/user/{user}",
     *     tags={"Roles"},
     *     summary="Assign role to user",
     *     description="Assigns a role to a specific user",
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
     *     path="/api/role/{role}/user/{user}",
     *     tags={"Roles"},
     *     summary="Remove role from user",
     *     description="Removes a role from a specific user",
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
     *         description="Role removed successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Role or user not found"
     *     )
     * )
     */
    public function rolesRemoveUser($role, $user)
    {
        $response = $this->repositoryObj->rolesRemoveUser($role, $user);
        if (!$response["success"]) {
            return $this->sendError('The Record Could not found.', ['error' => $response["message"]]);
        }
        return $this->sendResponse($response["data"], $response["message"]);
    }
}
