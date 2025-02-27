<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repository\SaasModules\PaypalManagement\PaypalManagementInterface;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="PayPal",
 *     description="API Endpoints for PayPal Payment Gateway Management"
 * )
 */
class PaypalController extends BaseController
{
    public $repositoryObj;

    public function __construct(PaypalManagementInterface $paypalObj){
        $this->repositoryObj = $paypalObj;
    }

    /**
     * @OA\Post(
     *     path="/api/paypal",
     *     tags={"PayPal"},
     *     summary="Configure PayPal gateway",
     *     description="Store PayPal gateway configuration settings",
     *     security={{"apiAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"client_id", "client_secret", "mode"},
     *             @OA\Property(property="client_id", type="string", example="YOUR_PAYPAL_CLIENT_ID"),
     *             @OA\Property(property="client_secret", type="string", example="YOUR_PAYPAL_CLIENT_SECRET"),
     *             @OA\Property(property="mode", type="string", enum={"sandbox", "live"}, example="sandbox"),
     *             @OA\Property(property="status", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="PayPal configuration stored successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="client_id", type="string"),
     *                 @OA\Property(property="mode", type="string"),
     *                 @OA\Property(property="status", type="boolean"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             ),
     *             @OA\Property(property="message", type="string", example="PayPal configuration stored successfully")
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
            return $this->sendError('Unable to store information.', ['error' => $response["message"]]);
        }
        return $this->sendResponse($response["data"], $response["message"]);
    }

    /**
     * @OA\Get(
     *     path="/api/paypal/info",
     *     tags={"PayPal"},
     *     summary="Get PayPal gateway information",
     *     description="Returns the current PayPal gateway configuration settings",
     *     security={{"apiAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="client_id", type="string"),
     *                 @OA\Property(property="mode", type="string", enum={"sandbox", "live"}),
     *                 @OA\Property(property="status", type="boolean"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             ),
     *             @OA\Property(property="message", type="string", example="PayPal configuration retrieved successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="PayPal configuration not found"
     *     )
     * )
     */
    public function getPaypalGatewayInformation(){
        $response = $this->repositoryObj->getPaypalGatewayInformation();

        if(! $response["success"]){
            return $this->sendError('Unable to get information', ['error' => $response["message"]]);
        }
        return $this->sendResponse($response["data"], $response["message"]);
    }

    /**
     * @OA\Put(
     *     path="/api/paypal/{id}",
     *     tags={"PayPal"},
     *     summary="Update PayPal gateway configuration",
     *     description="Updates the existing PayPal gateway configuration settings",
     *     security={{"apiAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of PayPal configuration to update",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="client_id", type="string", example="UPDATED_PAYPAL_CLIENT_ID"),
     *             @OA\Property(property="client_secret", type="string", example="UPDATED_PAYPAL_CLIENT_SECRET"),
     *             @OA\Property(property="mode", type="string", enum={"sandbox", "live"}, example="live"),
     *             @OA\Property(property="status", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="PayPal configuration updated successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="client_id", type="string"),
     *                 @OA\Property(property="mode", type="string"),
     *                 @OA\Property(property="status", type="boolean"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             ),
     *             @OA\Property(property="message", type="string", example="PayPal configuration updated successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="PayPal configuration not found"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input"
     *     )
     * )
     */
    public function update($id, Request $request){
        $response = $this->repositoryObj->update($id, $request);

        if(! $response["success"]){
            return $this->sendError('Unable to update stripe information', ['error' => $response["message"]]);
        }
        return $this->sendResponse($response["data"], $response["message"]);
    }
}
