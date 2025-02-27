<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repository\SaasModules\TimeZoneManagement\TimeZoneManagementInterface;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Timezones",
 *     description="API Endpoints for Timezone Management"
 * )
 */
class TimeZoneController extends BaseController
{
    public $repositoryObj;

    public function __construct(TimeZoneManagementInterface $timezoneObj){
        $this->repositoryObj = $timezoneObj;
    }

    /**
     * @OA\Get(
     *     path="/api/timezones",
     *     tags={"Timezones"},
     *     summary="Get list of timezones",
     *     description="Returns paginated list of all available timezones",
     *     security={{"apiAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array", @OA\Items(
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="name", type="string", example="America/New_York"),
     *                 @OA\Property(property="offset", type="string", example="UTC-05:00"),
     *                 @OA\Property(property="diff_from_gtm", type="string", example="-5"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )),
     *             @OA\Property(property="message", type="string", example="Timezones retrieved successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No timezones found"
     *     )
     * )
     */
    public function index(Request $request){
        $response = $this->repositoryObj->index($request);
        if(! $response["success"]){
            return $this->sendError('No timezone found', ['error' => $response["message"]]);
        }
        return $this->sendResponse($response["data"], $response["message"]);
    }

    /**
     * @OA\Post(
     *     path="/api/timezones",
     *     tags={"Timezones"},
     *     summary="Create new timezone",
     *     description="Creates a new timezone entry",
     *     security={{"apiAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "offset", "diff_from_gtm"},
     *             @OA\Property(property="name", type="string", example="America/New_York"),
     *             @OA\Property(property="offset", type="string", example="UTC-05:00"),
     *             @OA\Property(property="diff_from_gtm", type="string", example="-5")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Timezone created successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="offset", type="string"),
     *                 @OA\Property(property="diff_from_gtm", type="string"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             ),
     *             @OA\Property(property="message", type="string", example="Timezone created successfully")
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
            return $this->sendError('Unable to store timezone', ['error' => $response["message"]]);
        }
        return $this->sendResponse($response["data"], $response["message"]);
    }

    /**
     * @OA\Put(
     *     path="/api/timezones/{id}",
     *     tags={"Timezones"},
     *     summary="Update timezone",
     *     description="Updates an existing timezone entry",
     *     security={{"apiAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of timezone to update",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="America/New_York"),
     *             @OA\Property(property="offset", type="string", example="UTC-05:00"),
     *             @OA\Property(property="diff_from_gtm", type="string", example="-5")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Timezone updated successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="offset", type="string"),
     *                 @OA\Property(property="diff_from_gtm", type="string"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             ),
     *             @OA\Property(property="message", type="string", example="Timezone updated successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Timezone not found"
     *     )
     * )
     */
    public function update($id, Request $request){
        $response = $this->repositoryObj->update($id, $request);
        if(!$response["success"]){
            return $this->sendError('Unable to update timezone', ['error' => $response["message"]]);
        }
        return $this->sendResponse($response["data"], $response["message"]);
    }

    /**
     * @OA\Delete(
     *     path="/api/timezones/{id}",
     *     tags={"Timezones"},
     *     summary="Delete timezone",
     *     description="Deletes an existing timezone entry",
     *     security={{"apiAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of timezone to delete",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Timezone deleted successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object"),
     *             @OA\Property(property="message", type="string", example="Timezone deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Timezone not found"
     *     )
     * )
     */
    public function destroy($id){
        $response = $this->repositoryObj->destroy($id);
        if(!$response["success"]){
            return $this->sendError('Unable to delete timezone', ['error' => $response["message"]]);
        }
        return $this->sendResponse($response["data"], $response["message"]);
    }

    /**
     * @OA\Get(
     *     path="/api/all-timezones",
     *     tags={"Timezones"},
     *     summary="Get all timezones",
     *     description="Returns list of all timezones without pagination",
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
     *                 @OA\Property(property="offset", type="string"),
     *                 @OA\Property(property="diff_from_gtm", type="string"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )),
     *             @OA\Property(property="message", type="string", example="All timezones retrieved successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No timezones found"
     *     )
     * )
     */
    public function getAllTimezones(){
        $response = $this->repositoryObj->getAllTimezones();
        if(!$response["success"]){
            return $this->sendError('No timezones found', ['error' => $response["message"]]);
        }
        return $this->sendResponse($response["data"], $response["message"]);
    }
}
