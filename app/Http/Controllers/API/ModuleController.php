<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repository\SaasModules\ModuleManagement\ModuleManagementInterface;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Modules",
 *     description="API Endpoints for Module Management"
 * )
 */
class ModuleController extends BaseController
{
    public $repositoryObj;

    public function __construct(ModuleManagementInterface $moduleObj){
        $this->repositoryObj = $moduleObj;
    }

    /**
     * @OA\Get(
     *     path="/api/modules",
     *     tags={"Modules"},
     *     summary="Get list of modules",
     *     description="Returns list of all available modules with their features",
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
     *                 @OA\Property(property="status", type="boolean"),
     *                 @OA\Property(property="features", type="array", @OA\Items(
     *                     @OA\Property(property="id", type="integer"),
     *                     @OA\Property(property="name", type="string"),
     *                     @OA\Property(property="description", type="string")
     *                 )),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )),
     *             @OA\Property(property="message", type="string", example="Modules retrieved successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No modules found"
     *     )
     * )
     */
    public function index(Request $request){
        $response = $this->repositoryObj->index($request);

        if(! $response["success"]){
            return $this->sendError('No modules found.', ['error' => $response["message"]]);
        }
        return $this->sendResponse($response["data"], $response["message"]);
    }

    /**
     * @OA\Post(
     *     path="/api/createModule",
     *     tags={"Modules"},
     *     summary="Create new module",
     *     description="Creates a new module with specified features",
     *     security={{"apiAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "description"},
     *             @OA\Property(property="name", type="string", example="User Management"),
     *             @OA\Property(property="description", type="string", example="Module for managing users"),
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="features", type="array", @OA\Items(type="integer"), example={1,2,3})
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Module created successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object"),
     *             @OA\Property(property="message", type="string", example="Module created successfully")
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
        if(!$response["success"]){
            return $this->sendError('Unable to store module', ['error' => $response["message"]]);
        }
        return $this->sendResponse($response["data"], $response["message"]);
    }

    /**
     * @OA\Post(
     *     path="/api/updateModule/{id}",
     *     tags={"Modules"},
     *     summary="Update module",
     *     description="Updates an existing module",
     *     security={{"apiAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of module to update",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="description", type="string"),
     *             @OA\Property(property="status", type="boolean"),
     *             @OA\Property(property="features", type="array", @OA\Items(type="integer"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Module updated successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object"),
     *             @OA\Property(property="message", type="string", example="Module updated successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Module not found"
     *     )
     * )
     */
    public function update($id, Request $request){
        $response = $this->repositoryObj->update($id, $request);
        if(!$response["success"]){
            return $this->sendError('Unable to update module', ['error' => $response["message"]]);
        }
        return $this->sendResponse($response["data"], $response["message"]);
    }

    /**
     * @OA\Delete(
     *     path="/api/deleteModule/{id}",
     *     tags={"Modules"},
     *     summary="Delete module",
     *     description="Deletes an existing module",
     *     security={{"apiAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of module to delete",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Module deleted successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object"),
     *             @OA\Property(property="message", type="string", example="Module deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Module not found"
     *     )
     * )
     */
    public function destroy($id){
        $response = $this->repositoryObj->destroy($id);
        if(!$response["success"]){
            return $this->sendError('Unable to delete module', ['error' => $response["message"]]);
        }
        return $this->sendResponse($response["data"], $response["message"]);
    }
}
