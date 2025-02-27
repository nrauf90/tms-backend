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
}
