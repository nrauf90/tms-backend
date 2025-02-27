<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repository\SaasModules\CountryManagement\CountryManagementInterface;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Countries",
 *     description="API Endpoints for Country Management"
 * )
 */
class CountryController extends BaseController
{
    public $repositoryObj;

    public function __construct(CountryManagementInterface $countryObj){
        $this->repositoryObj = $countryObj;
    }

    /**
     * @OA\Get(
     *     path="/api/countries",
     *     tags={"Countries"},
     *     summary="Get list of countries",
     *     description="Returns list of all available countries",
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
     *                 @OA\Property(property="code", type="string"),
     *                 @OA\Property(property="phone_code", type="string"),
     *                 @OA\Property(property="currency", type="string"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )),
     *             @OA\Property(property="message", type="string", example="Countries retrieved successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No countries found"
     *     )
     * )
     */
    public function index(Request $request){
        $response = $this->repositoryObj->index($request);
        if(! $response["success"]){
            return $this->sendError('No country found', ['error' => $response["message"]]);
        }
        return $this->sendResponse($response["data"], $response["message"]);
    }

    /**
     * @OA\Get(
     *     path="/api/response-testing",
     *     tags={"Testing"},
     *     summary="Test API response format",
     *     description="Returns a sample API response for testing purposes",
     *     @OA\Response(
     *         response=200,
     *         description="Sample response",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="statusCode", type="object",
     *                 @OA\Property(property="code", type="integer", example=200),
     *                 @OA\Property(property="description", type="string", example="Operation Successfully Performed")
     *             ),
     *             @OA\Property(property="message", type="string", example="Terminal Created Successfully"),
     *             @OA\Property(property="payload", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="terminal_name", type="string", example="Terminal Smart"),
     *                 @OA\Property(property="latitude", type="string", example="33.6224130"),
     *                 @OA\Property(property="longitude", type="string", example="longitude")
     *             ),
     *             @OA\Property(property="error", type="array", @OA\Items(type="string")),
     *             @OA\Property(property="isSuccess", type="boolean", example=true)
     *         )
     *     )
     * )
     */
    public function responseTesting(){
        // add API
        $response["statusCode"]["code"] = 200;
        $response["statusCode"]["description"] = "Operation Successfully Performed";
        $response["message"] = "Terminal Created Successfully";
        $response["payload"]["id"] = 1;
        $response["payload"]["terminal_name"] = "Terminal Smart";
        $response["payload"]["latitude"] = "33.6224130";
        $response["payload"]["longitude"] = "longitude";
        $response["error"] = [];
        $response["isSuccess"] = true;

        return response()->json($response, 200);
    }
}
