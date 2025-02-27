<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repository\RolesPermissions\PermissionGroupManagement\PermissionGroupManagementInterface;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Permission Groups",
 *     description="API Endpoints for Permission Group Management"
 * )
 */
class PermissionGroupController extends BaseController
{
    public $repositoryObj;

    public function __construct(PermissionGroupManagementInterface $groupObj){
        $this->repositoryObj = $groupObj;
    }

    /**
     * @OA\Get(
     *     path="/api/groups",
     *     tags={"Permission Groups"},
     *     summary="Get list of permission groups",
     *     description="Returns list of all permission groups",
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
     *                 @OA\Property(property="description", type="string"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )),
     *             @OA\Property(property="message", type="string", example="Permission groups retrieved successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No groups found"
     *     )
     * )
     */
    public function index(Request $request){
        $response = $this->repositoryObj->index($request);

        if(! $response["success"]){
            return $this->sendError('No group found', ['error' => $response["message"]]);
        }
        return $this->sendResponse($response["data"], $response["message"]);
    }

    /**
     * @OA\Post(
     *     path="/api/createGroup",
     *     tags={"Permission Groups"},
     *     summary="Create new permission group",
     *     description="Creates a new permission group",
     *     security={{"apiAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "description"},
     *             @OA\Property(property="name", type="string", example="Admin Group"),
     *             @OA\Property(property="description", type="string", example="Group for administrative permissions")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Permission group created successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="description", type="string"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             ),
     *             @OA\Property(property="message", type="string", example="Permission group created successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input"
     *     )
     * )
     */
    public function store(Request $request){
        $response = $this->repositoryObj->store($request);

        if(! $response["success"]){
            return $this->sendError('Unable to store group', ['error' => $response["message"]]);
        }
        return $this->sendResponse($response["data"], $response["message"]);
    }

    /**
     * @OA\Post(
     *     path="/api/updateGroup/{id}",
     *     tags={"Permission Groups"},
     *     summary="Update permission group",
     *     description="Updates an existing permission group",
     *     security={{"apiAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of permission group to update",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Updated Admin Group"),
     *             @OA\Property(property="description", type="string", example="Updated group description")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Permission group updated successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object"),
     *             @OA\Property(property="message", type="string", example="Permission group updated successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Permission group not found"
     *     )
     * )
     */
    public function update($id, Request $request){
        $response = $this->repositoryObj->update($id, $request);

        if(! $response["success"]){
            return $this->sendError('Unable to update group', ['error' => $response["message"]]);
        }
        return $this->sendResponse($response["data"], $response["message"]);
    }

    /**
     * @OA\Delete(
     *     path="/api/deleteGroup/{id}",
     *     tags={"Permission Groups"},
     *     summary="Delete permission group",
     *     description="Deletes an existing permission group",
     *     security={{"apiAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of permission group to delete",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Permission group deleted successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object"),
     *             @OA\Property(property="message", type="string", example="Permission group deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Permission group not found"
     *     )
     * )
     */
    public function destroy($id){
        $response = $this->repositoryObj->destroy($id);

        if(! $response["success"]){
            return $this->sendError('Unable to delete group', ['error' => $response["message"]]);
        }
        return $this->sendResponse($response["data"], $response["message"]);
    }
}
