<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repository\SaasModules\StripeManagement\StripeManagementInterface;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Stripe",
 *     description="API Endpoints for Stripe Payment Gateway Management"
 * )
 */
class StripeController extends BaseController
{
    public $repositoryObj;

    public function __construct(StripeManagementInterface $stripeObj){
        $this->repositoryObj = $stripeObj;
    }

    /**
     * @OA\Post(
     *     path="/api/stripe",
     *     tags={"Stripe"},
     *     summary="Configure Stripe gateway",
     *     description="Store Stripe gateway configuration settings",
     *     security={{"apiAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"publishable_key", "secret_key"},
     *             @OA\Property(property="publishable_key", type="string", example="pk_test_..."),
     *             @OA\Property(property="secret_key", type="string", example="sk_test_..."),
     *             @OA\Property(property="status", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Stripe configuration stored successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="publishable_key", type="string"),
     *                 @OA\Property(property="status", type="boolean"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             ),
     *             @OA\Property(property="message", type="string", example="Stripe configuration stored successfully")
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
     *     path="/api/stripe/info",
     *     tags={"Stripe"},
     *     summary="Get Stripe gateway information",
     *     description="Returns the current Stripe gateway configuration settings",
     *     security={{"apiAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="publishable_key", type="string"),
     *                 @OA\Property(property="status", type="boolean"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             ),
     *             @OA\Property(property="message", type="string", example="Stripe configuration retrieved successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Stripe configuration not found"
     *     )
     * )
     */
    public function getStripeGatewayInformation(){
        $response = $this->repositoryObj->getStripeGatewayInformation();

        if(! $response["success"]){
            return $this->sendError('Unable to get information', ['error' => $response["message"]]);
        }
        return $this->sendResponse($response["data"], $response["message"]);
    }

    /**
     * @OA\Put(
     *     path="/api/stripe/{id}",
     *     tags={"Stripe"},
     *     summary="Update Stripe gateway configuration",
     *     description="Updates the existing Stripe gateway configuration settings",
     *     security={{"apiAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of Stripe configuration to update",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="publishable_key", type="string", example="pk_test_..."),
     *             @OA\Property(property="secret_key", type="string", example="sk_test_..."),
     *             @OA\Property(property="status", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Stripe configuration updated successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="publishable_key", type="string"),
     *                 @OA\Property(property="status", type="boolean"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             ),
     *             @OA\Property(property="message", type="string", example="Stripe configuration updated successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Stripe configuration not found"
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

    /**
     * @OA\Post(
     *     path="/api/stripe/process-payment",
     *     tags={"Stripe"},
     *     summary="Process a payment through Stripe",
     *     description="Process a payment using Stripe payment gateway",
     *     security={{"apiAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"amount", "currency", "payment_method", "description"},
     *             @OA\Property(property="amount", type="number", format="float", example=99.99),
     *             @OA\Property(property="currency", type="string", example="USD"),
     *             @OA\Property(property="payment_method", type="string", example="pm_card_visa"),
     *             @OA\Property(property="description", type="string", example="Payment for Package XYZ")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Payment processed successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="payment_id", type="string"),
     *                 @OA\Property(property="amount", type="number"),
     *                 @OA\Property(property="currency", type="string"),
     *                 @OA\Property(property="status", type="string"),
     *                 @OA\Property(property="created_at", type="string", format="date-time")
     *             ),
     *             @OA\Property(property="message", type="string", example="Payment processed successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Payment processing failed"
     *     )
     * )
     */
    public function processPayment(Request $request){
        $response = $this->repositoryObj->processPayment($request);
        if(! $response["success"]){
            return $this->sendError('Payment processing failed', ['error' => $response["message"]]);
        }
        return $this->sendResponse($response["data"], $response["message"]);
    }
}
