<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repository\SaasModules\OfflinePaymentManagement\OfflinePaymentManagementInterface;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Offline Payments",
 *     description="API Endpoints for Offline Payment Method Management"
 * )
 */
class OfflinePaymentController extends BaseController
{
    public $repositoryObj;

    public function __construct(OfflinePaymentManagementInterface $paymentObj){
        $this->repositoryObj = $paymentObj;
    }

    /**
     * @OA\Get(
     *     path="/api/offlineMethods",
     *     tags={"Offline Payments"},
     *     summary="Get all offline payment methods",
     *     description="Returns list of all configured offline payment methods",
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
     *                 @OA\Property(property="instructions", type="string"),
     *                 @OA\Property(property="status", type="boolean"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )),
     *             @OA\Property(property="message", type="string", example="Payment methods retrieved successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No payment methods found"
     *     )
     * )
     */
    public function index(Request $request){
        $response = $this->repositoryObj->index($request);
        if(! $response["success"]){
           return $this->sendError('No method found', ['error' => $response["message"]]);
        }
        return $this->sendResponse($response["data"], $response["message"]);
    }

    /**
     * @OA\Post(
     *     path="/api/createOfflineMethod",
     *     tags={"Offline Payments"},
     *     summary="Create new offline payment method",
     *     description="Creates a new offline payment method",
     *     security={{"apiAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "description", "instructions"},
     *             @OA\Property(property="name", type="string", example="Bank Transfer"),
     *             @OA\Property(property="description", type="string", example="Direct bank transfer payment"),
     *             @OA\Property(property="instructions", type="string", example="Please transfer the amount to Account #12345"),
     *             @OA\Property(property="status", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Payment method created successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="description", type="string"),
     *                 @OA\Property(property="instructions", type="string"),
     *                 @OA\Property(property="status", type="boolean"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             ),
     *             @OA\Property(property="message", type="string", example="Payment method created successfully")
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
            return $this->sendError('Unable to store method', ['error' => $response["message"]]);
        }
        return $this->sendResponse($response["data"], $response["message"]);
    }

    /**
     * @OA\Post(
     *     path="/api/updateOfflineMethod/{id}",
     *     tags={"Offline Payments"},
     *     summary="Update offline payment method",
     *     description="Updates an existing offline payment method",
     *     security={{"apiAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of payment method to update",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Updated Bank Transfer"),
     *             @OA\Property(property="description", type="string", example="Updated bank transfer payment"),
     *             @OA\Property(property="instructions", type="string", example="Updated transfer instructions"),
     *             @OA\Property(property="status", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Payment method updated successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="description", type="string"),
     *                 @OA\Property(property="instructions", type="string"),
     *                 @OA\Property(property="status", type="boolean"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             ),
     *             @OA\Property(property="message", type="string", example="Payment method updated successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Payment method not found"
     *     )
     * )
     */
    public function update($id, Request $request){
        $response = $this->repositoryObj->update($id, $request);
        if(!$response["success"]){
            return $this->sendError('Unable to update method', ['error' => $response["message"]]);
        }
        return $this->sendResponse($response["data"], $response["message"]);
    }

    /**
     * @OA\Delete(
     *     path="/api/deleteOfflineMethod/{id}",
     *     tags={"Offline Payments"},
     *     summary="Delete offline payment method",
     *     description="Deletes an existing offline payment method",
     *     security={{"apiAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of payment method to delete",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Payment method deleted successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object"),
     *             @OA\Property(property="message", type="string", example="Payment method deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Payment method not found"
     *     )
     * )
     */
    public function destroy($id){
        $response = $this->repositoryObj->destroy($id);
        if(!$response["success"]){
            return $this->sendError('Unable to delete method', ['error' => $response["message"]]);
        }
        return $this->sendResponse($response["data"], $response["message"]);
    }
}
