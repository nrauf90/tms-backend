<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repository\SaasModules\WeekManagement\WeekManagementInterface;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Week Days",
 *     description="API Endpoints for Week Days Management"
 * )
 */
class WeekController extends BaseController
{
    public $repositoryObj;
    
    public function __construct(WeekManagementInterface $weekObj){
        $this->repositoryObj = $weekObj;
    }

    /**
     * @OA\Get(
     *     path="/api/weeks",
     *     tags={"Week Days"},
     *     summary="Get list of week days",
     *     description="Returns list of all week days",
     *     security={{"apiAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array", @OA\Items(
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="name", type="string", example="Monday"),
     *                 @OA\Property(property="short_name", type="string", example="Mon"),
     *                 @OA\Property(property="status", type="boolean"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )),
     *             @OA\Property(property="message", type="string", example="Week days retrieved successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No week days found"
     *     )
     * )
     */
    public function index(Request $request){
        $response = $this->repositoryObj->index($request);

        if(! $response["success"]){
            return $this->sendError('Days not found', ['error' => $response["message"]]);
        }

        return $this->sendResponse($response["data"], $response["message"]);
    }

    /**
     * @OA\Post(
     *     path="/api/weeks",
     *     tags={"Week Days"},
     *     summary="Create new week day",
     *     description="Creates a new week day entry",
     *     security={{"apiAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "short_name"},
     *             @OA\Property(property="name", type="string", example="Monday"),
     *             @OA\Property(property="short_name", type="string", example="Mon"),
     *             @OA\Property(property="status", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Week day created successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="short_name", type="string"),
     *                 @OA\Property(property="status", type="boolean"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             ),
     *             @OA\Property(property="message", type="string", example="Week day created successfully")
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
            return $this->sendError('Unable to store week day', ['error' => $response["message"]]);
        }
        return $this->sendResponse($response["data"], $response["message"]);
    }

    /**
     * @OA\Put(
     *     path="/api/weeks/{id}",
     *     tags={"Week Days"},
     *     summary="Update week day",
     *     description="Updates an existing week day entry",
     *     security={{"apiAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of week day to update",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Monday"),
     *             @OA\Property(property="short_name", type="string", example="Mon"),
     *             @OA\Property(property="status", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Week day updated successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="short_name", type="string"),
     *                 @OA\Property(property="status", type="boolean"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             ),
     *             @OA\Property(property="message", type="string", example="Week day updated successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Week day not found"
     *     )
     * )
     */
    public function update($id, Request $request){
        $response = $this->repositoryObj->update($id, $request);
        if(!$response["success"]){
            return $this->sendError('Unable to update week day', ['error' => $response["message"]]);
        }
        return $this->sendResponse($response["data"], $response["message"]);
    }

    /**
     * @OA\Delete(
     *     path="/api/weeks/{id}",
     *     tags={"Week Days"},
     *     summary="Delete week day",
     *     description="Deletes an existing week day entry",
     *     security={{"apiAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of week day to delete",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Week day deleted successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object"),
     *             @OA\Property(property="message", type="string", example="Week day deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Week day not found"
     *     )
     * )
     */
    public function destroy($id){
        $response = $this->repositoryObj->destroy($id);
        if(!$response["success"]){
            return $this->sendError('Unable to delete week day', ['error' => $response["message"]]);
        }
        return $this->sendResponse($response["data"], $response["message"]);
    }

    /**
     * @OA\Get(
     *     path="/api/all-weeks",
     *     tags={"Week Days"},
     *     summary="Get all week days",
     *     description="Returns list of all week days without pagination",
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
     *                 @OA\Property(property="short_name", type="string"),
     *                 @OA\Property(property="status", type="boolean"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )),
     *             @OA\Property(property="message", type="string", example="All week days retrieved successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No week days found"
     *     )
     * )
     */
    public function getAllWeeks(){
        $response = $this->repositoryObj->getAllWeeks();
        if(!$response["success"]){
            return $this->sendError('No week days found', ['error' => $response["message"]]);
        }
        return $this->sendResponse($response["data"], $response["message"]);
    }
}
