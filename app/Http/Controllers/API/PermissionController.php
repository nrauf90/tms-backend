<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repository\RolesPermissions\PermissionManagement\PermissionManagementInterface;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Permissions",
 *     description="API Endpoints for Permission Management"
 * )
 */
class PermissionController extends BaseController
{
    public $repositoryObj;

    public function __construct(PermissionManagementInterface $permissionObj)
    {
        $this->repositoryObj = $permissionObj;
    }

    /**
     * @OA\Get(
     *     path="/api/permission",
     *     tags={"Permissions"},
     *     summary="Get list of permissions",
     *     description="Returns list of all permissions",
     *     security={{"apiAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array", @OA\Items(type="object")),
     *             @OA\Property(property="message", type="string", example="Permissions retrieved successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No permissions found"
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
     * @OA\Delete(
     *     path="/api/permission/{permission}",
     *     tags={"Permissions"},
     *     summary="Delete permission",
     *     description="Deletes a permission",
     *     @OA\Parameter(
     *         name="permission",
     *         in="path",
     *         description="Permission to delete",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Permission deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Permission not found"
     *     )
     * )
     */
    public function destroy($permission)
    {
        $response = $this->repositoryObj->destroy($permission);
        if (!$response["success"]) {
            return $this->sendError('The Record Could not be deleted.', ['error' => $response["message"]]);
        }
        return $this->sendResponse($response["data"], $response["message"]);
    }

    /**
     * @OA\Post(
     *     path="/api/permission/{permission}",
     *     tags={"Permissions"},
     *     summary="Create new permission",
     *     description="Creates a new permission with the specified name",
     *     @OA\Parameter(
     *         name="permission",
     *         in="path",
     *         description="Name of the permission to create",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Permission created successfully"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input"
     *     )
     * )
     */
    public function createPermission($permission, Request $request)
    {
        $response = $this->repositoryObj->createPermission($permission, $request);
        if (!$response["success"]) {
            return $this->sendError('The Permission cannot be created.', ['error' => $response["message"]]);
        }
        return $this->sendResponse($response["data"], $response["message"]);
    }

    /**
     * @OA\Post(
     *     path="/api/permissions/{pname}/users/{permission}",
     *     tags={"Permissions"},
     *     summary="Assign permission to user",
     *     description="Assigns a permission to a specific user",
     *     security={{"apiAuth":{}}},
     *     @OA\Parameter(
     *         name="pname",
     *         in="path",
     *         description="Name or ID of the user",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="permission",
     *         in="path",
     *         description="Permission to assign",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Permission assigned successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User or permission not found"
     *     )
     * )
     */
    public function assignPermissionToUser($pname, $permission)
    {
        $response = $this->repositoryObj->assignPermissionToUser($pname, $permission);
        if (!$response["success"]) {
            return $this->sendError('The Record Could not be deleted.', ['error' => $response["message"]]);
        }
        return $this->sendResponse($response["data"], $response["message"]);
    }

    /**
     * @OA\Get(
     *     path="/api/permissions/users/{id}",
     *     tags={"Permissions"},
     *     summary="Get user permissions",
     *     description="Returns all permissions assigned to a specific user",
     *     security={{"apiAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the user",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array", @OA\Items(type="string")),
     *             @OA\Property(property="message", type="string", example="User permissions retrieved successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found"
     *     )
     * )
     */
    public function showPermissionsAssignedToUser($id)
    {
        $response = $this->repositoryObj->showPermissionsAssignedToUser($id);
        if (!$response["success"]) {
            return $this->sendError('permission Could not be viewed.', ['error' => $response["message"]]);
        }
        return $this->sendResponse($response["data"], $response["message"]);
    }

    /**
     * @OA\Delete(
     *     path="/api/permissions/{pname}/users/{permission}",
     *     tags={"Permissions"},
     *     summary="Revoke permission from user",
     *     description="Removes a permission from a specific user",
     *     security={{"apiAuth":{}}},
     *     @OA\Parameter(
     *         name="pname",
     *         in="path",
     *         description="Name or ID of the user",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="permission",
     *         in="path",
     *         description="Permission to revoke",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Permission revoked successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User or permission not found"
     *     )
     * )
     */
    public function revokePermissionFromUser($pname, $permission)
    {
        $response = $this->repositoryObj->revokePermissionFromUser($pname, $permission);
        if (!$response["success"]) {
            return $this->sendError('The Record Could not be deleted.', ['error' => $response["message"]]);
        }
        return $this->sendResponse($response["data"], $response["message"]);
    }

    /**
     * @OA\Post(
     *     path="/api/createPermissionGroup",
     *     tags={"Permissions"},
     *     summary="Create permission group",
     *     description="Creates a new permission group",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="description", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Permission group created successfully"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input"
     *     )
     * )
     */
    public function createPermissionGroup(Request $request)
    {
        $response = $this->repositoryObj->createPermissionGroup($request);
        if(!$response["success"]){
            return $this->sendError('The Permission Group cannot be created.',['error' => $response["message"]]);
        }
        return $this->sendResponse($response["data"], $response["message"]);
    }

    /**
     * @OA\Delete(
     *     path="/api/deletePermissionGroup/{id}",
     *     tags={"Permissions"},
     *     summary="Delete permission group",
     *     description="Deletes a permission group",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the permission group",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Permission group deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Permission group not found"
     *     )
     * )
     */
    public function deletePermissionGroup($id)
    {
        $response = $this->repositoryObj->deletePermissionGroup($id);
        if(!$response["success"]){
            return $this->sendError('This Record cannot be deleted.',['error' => $response["message"]]);
        }
        return $this->sendResponse($response["data"] , $response["message"]);
    }

    /**
     * @OA\Get(
     *     path="/api/getAllGroups",
     *     tags={"Permissions"},
     *     summary="Get all permission groups",
     *     description="Returns list of all permission groups",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     )
     * )
     */
    public function getGroups()
    {
        $response=$this->repositoryObj->getGroups();
        if(!$response["success"]){
            return $this->sendError('No Groups found. Please add group !' , ['error' => $response["message"]]);
        }
        return $this->sendResponse($response["data"] , $response["message"]);
    }

    /**
     * @OA\Post(
     *     path="/api/assignPermissiontoRole/{id}",
     *     tags={"Permissions"},
     *     summary="Assign permissions to role",
     *     description="Assigns multiple permissions to a role",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the role",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="permissions", type="array", @OA\Items(type="string"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Permissions assigned successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Role not found"
     *     )
     * )
     */
    public function assignPermissionToRole($id, Request $request)
    {
        $response=$this->repositoryObj->assignPermissionToRole($id, $request);
        if(!$response["success"]){
            return $this->sendError('This Record cannot be created.' , ['error' => $response["message"]]);
        }
        return $this->sendResponse($response["data"], $response["message"]);
    }

    /**
     * @OA\Post(
     *     path="/api/edit/permission/{id}",
     *     tags={"Permissions"},
     *     summary="Edit permission",
     *     description="Updates a permission's details",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the permission",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="description", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Permission updated successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Permission not found"
     *     )
     * )
     */
    public function editPermission($id, Request $request)
    {
        $response=$this->repositoryObj->editPermission($id, $request);
        if(! $response["success"]){
            return $this->sendError('Sorry cannot update permission.', ['error' => $response["message"]]);
        }
        return $this->sendResponse($response["data"], $response["message"]);
    }

    /**
     * @OA\Post(
     *     path="/api/addGenericPermissions",
     *     tags={"Permissions"},
     *     summary="Create generic permissions",
     *     description="Creates generic permissions for modules",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="permissions", type="array", @OA\Items(type="string"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Generic permissions created successfully"
     *     )
     * )
     */
    public function createGenericPermissions(Request $request)
    {
        $response=$this->repositoryObj->createGenericPermissions($request);
        if(! $response["success"]){
            return $this->sendError('Permission Cannot Be Created.', ['error' => $response["message"]]);
        }
        return $this->sendResponse($response["data"], $response["message"]);
    }

    /**
     * @OA\Get(
     *     path="/api/getGenericPermissions",
     *     tags={"Permissions"},
     *     summary="Get generic permissions",
     *     description="Returns list of generic permissions",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     )
     * )
     */
    public function getGenericPermissions()
    {
        $response = $this->repositoryObj->getGenericPermissions();
        if(! $response["success"]){
            return $this->sendError('Unable to get Permissions.', ['error' => $response["message"]]);
        }
        return $this->sendResponse($response["data"], $response["message"]);
    }

    /**
     * @OA\Get(
     *     path="/api/getGroupedPermissions",
     *     tags={"Permissions"},
     *     summary="Get grouped permissions",
     *     description="Returns permissions grouped by their groups",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     )
     * )
     */
    public function getGroupPermissions()
    {
        $response= $this->repositoryObj->getGroupPermissions();
        if(! $response["success"]){
            return $this->sendError('Unable to get grouped permissions.', ['error' => $response["message"]]);
        }
        return $this->sendResponse($response["data"], $response["message"]);
    }

    /**
     * @OA\Get(
     *     path="/api/getPermission/{id}",
     *     tags={"Permissions"},
     *     summary="Get permission by ID",
     *     description="Returns details of a specific permission",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the permission",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Permission not found"
     *     )
     * )
     */
    public function getPermission($id)
    {
        $response = $this->repositoryObj->getPermission($id);
        if(! $response["success"]){
            return $this->sendError('Unable to find permission.', ['error' => $response["message"]]);
        }
        return $this->sendResponse($response["data"], $response["message"]);
    }
}