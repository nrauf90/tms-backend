<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repository\SaasModules\CurrencyManagement\CurrencyManagementInterface;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Currencies",
 *     description="API Endpoints for managing currencies"
 * )
 */
class CurrencyController extends BaseController
{
    public $repositoryObj;

    public function __construct(CurrencyManagementInterface $currencyObj){
        $this->repositoryObj = $currencyObj;
    }

    /**
     * @OA\Get(
     *     path="/api/currencies",
     *     summary="Get paginated list of currencies",
     *     description="Returns a paginated list of all currencies in the system",
     *     operationId="currencyIndex",
     *     tags={"Currencies"},
     *     security={{ "bearerAuth": {} }},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page number for pagination",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array", @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="US Dollar"),
     *                 @OA\Property(property="code", type="string", example="USD"),
     *                 @OA\Property(property="symbol", type="string", example="$")
     *             )),
     *             @OA\Property(property="message", type="string", example="Currencies retrieved successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No currencies found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="No currency found"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     )
     * )
     */
    public function index(Request $request){
        $response = $this->repositoryObj->index($request);
        if(! $response["success"]){
            return $this->sendError('No currency found.', ['error' => $response["message"]]);
        }
        return $this->sendResponse($response["data"], $response["message"]);
    }

    /**
     * @OA\Post(
     *     path="/api/createCurrency",
     *     summary="Create a new currency",
     *     description="Creates a new currency with the provided details",
     *     operationId="storeCurrency",
     *     tags={"Currencies"},
     *     security={{ "bearerAuth": {} }},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "code", "symbol"},
     *             @OA\Property(property="name", type="string", example="Euro"),
     *             @OA\Property(property="code", type="string", example="EUR"),
     *             @OA\Property(property="symbol", type="string", example="€")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Currency created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Euro"),
     *                 @OA\Property(property="code", type="string", example="EUR"),
     *                 @OA\Property(property="symbol", type="string", example="€")
     *             ),
     *             @OA\Property(property="message", type="string", example="Currency created successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unable to store currency"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     )
     * )
     */
    public function store(Request $request){
        $response = $this->repositoryObj->store($request);
        if(! $response["success"]){
            return $this->sendError('Unable to store currency', ['error' => $response["message"]]);
        }
        return $this->sendResponse($response["data"], $response["message"]);
    }

    /**
     * @OA\Post(
     *     path="/api/updateCurrency/{id}",
     *     summary="Update an existing currency",
     *     description="Updates a currency's details by ID",
     *     operationId="updateCurrency",
     *     tags={"Currencies"},
     *     security={{ "bearerAuth": {} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the currency to update",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Updated Euro"),
     *             @OA\Property(property="code", type="string", example="EUR"),
     *             @OA\Property(property="symbol", type="string", example="€")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Currency updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object"),
     *             @OA\Property(property="message", type="string", example="Currency updated successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Currency not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unable to update currency"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     )
     * )
     */
    public function update($id, Request $request){
        $response = $this->repositoryObj->update($id, $request);
        if(!$response["success"]){
            return $this->sendError('Unable to update currency', ['error' => $response["message"]]);
        }
        return $this->sendResponse($response["data"], $response["message"]);
    }

    /**
     * @OA\Delete(
     *     path="/api/deleteCurrency/{id}",
     *     summary="Delete a currency",
     *     description="Deletes a currency by ID",
     *     operationId="destroyCurrency",
     *     tags={"Currencies"},
     *     security={{ "bearerAuth": {} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the currency to delete",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Currency deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object"),
     *             @OA\Property(property="message", type="string", example="Currency deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Currency not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unable to delete currency"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     )
     * )
     */
    public function destroy($id){
        $response = $this->repositoryObj->destroy($id);
        if(! $response["success"]){
            return $this->sendError('Unable to delete currency', ['error' => $response["message"]]);
        }
        return $this->sendResponse($response["data"], $response["message"]);
    }

    /**
     * @OA\Get(
     *     path="/api/allCurrencies",
     *     summary="Get all currencies",
     *     description="Returns a list of all currencies without pagination",
     *     operationId="getAllCurrencies",
     *     tags={"Currencies"},
     *     security={{ "bearerAuth": {} }},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array", @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="US Dollar"),
     *                 @OA\Property(property="code", type="string", example="USD"),
     *                 @OA\Property(property="symbol", type="string", example="$")
     *             )),
     *             @OA\Property(property="message", type="string", example="Currencies retrieved successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No currencies found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="No Currency Found"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     )
     * )
     */
    public function getAllCurrencies(){
        $response = $this->repositoryObj->getAllCurrencies();
        if(! $response["success"]){
            return $this->sendError('No Currency Found', ['error' => $response["message"]]);
        }
        return $this->sendResponse($response["data"], $response["message"]);
    }
    
}
