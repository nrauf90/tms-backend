<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repository\ResponseTesting\ResponseTestingInterface;
use OpenApi\Annotations as OA;
use App\Http\Controllers\API\BaseController as BaseController;

/**
 * @OA\Tag(
 *     name="Testing",
 *     description="API Endpoints for Response Testing"
 * )
 */
class ResponseTestingController extends BaseController
{
    public $repositoryObj;

    public function __construct(ResponseTestingInterface $responseObj){
        $this->repositoryObj = $responseObj;
    }

    /**
     * @OA\Get(
     *     path="/api/responseTesting",
     *     tags={"Testing"},
     *     summary="Test API response format",
     *     description="Returns a sample API response for testing purposes",
     *     security={{"apiAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Sample response",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="statusCode", type="object",
     *                 @OA\Property(property="code", type="integer", example=200),
     *                 @OA\Property(property="description", type="string", example="Operation Successfully Performed")
     *             ),
     *             @OA\Property(property="message", type="string", example="Test Response"),
     *             @OA\Property(property="payload", type="object"),
     *             @OA\Property(property="error", type="array", @OA\Items(type="string")),
     *             @OA\Property(property="isSuccess", type="boolean", example=true)
     *         )
     *     )
     * )
     */
    public function index(Request $request){
        return $this->repositoryObj->index($request);
    }
}
