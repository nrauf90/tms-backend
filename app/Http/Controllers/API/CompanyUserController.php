<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repository\CompanyUserManagement\CompanyUserManagementInterface;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Company Users",
 *     description="API Endpoints for Company User Management"
 * )
 */
class CompanyUserController extends Controller
{
    public $repositoryObj;

    public function __construct(CompanyUserManagementInterface $companyUserObj){
        $this->repositoryObj = $companyUserObj;
    }

    /**
     * @OA\Get(
     *     path="/api/companyUsers",
     *     tags={"Company Users"},
     *     summary="Get list of company users",
     *     description="Returns paginated list of users associated with the company",
     *     security={{"apiAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="current_page", type="integer"),
     *                 @OA\Property(property="data", type="array", @OA\Items(
     *                     @OA\Property(property="id", type="integer"),
     *                     @OA\Property(property="company_id", type="integer"),
     *                     @OA\Property(property="user_id", type="integer"),
     *                     @OA\Property(property="role", type="string"),
     *                     @OA\Property(property="status", type="boolean"),
     *                     @OA\Property(property="user", type="object",
     *                         @OA\Property(property="id", type="integer"),
     *                         @OA\Property(property="name", type="string"),
     *                         @OA\Property(property="email", type="string")
     *                     ),
     *                     @OA\Property(property="created_at", type="string", format="date-time"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time")
     *                 )),
     *                 @OA\Property(property="total", type="integer"),
     *                 @OA\Property(property="per_page", type="integer")
     *             ),
     *             @OA\Property(property="message", type="string", example="Company users retrieved successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No users found"
     *     )
     * )
     */
    public function index(Request $request){
        return $this->repositoryObj->index($request);
    }

    /**
     * @OA\Post(
     *     path="/api/createCompanyUser",
     *     tags={"Company Users"},
     *     summary="Add user to company",
     *     description="Associates a user with the company",
     *     security={{"apiAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"user_id", "role"},
     *             @OA\Property(property="user_id", type="integer", example=1),
     *             @OA\Property(property="role", type="string", example="employee"),
     *             @OA\Property(property="status", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User added to company successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object"),
     *             @OA\Property(property="message", type="string", example="User added to company successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input"
     *     )
     * )
     */
    public function store(Request $request){
        return $this->repositoryObj->store($request);
    }

    /**
     * @OA\Post(
     *     path="/api/updateCompanyUser/{id}",
     *     tags={"Company Users"},
     *     summary="Update company user",
     *     description="Updates the role or status of a company user",
     *     security={{"apiAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of company user to update",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="role", type="string", example="manager"),
     *             @OA\Property(property="status", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Company user updated successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object"),
     *             @OA\Property(property="message", type="string", example="Company user updated successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Company user not found"
     *     )
     * )
     */
    public function update($id, Request $request){
        return $this->repositoryObj->update($id, $request);
    }

    /**
     * @OA\Delete(
     *     path="/api/deleteCompanyUser/{id}",
     *     tags={"Company Users"},
     *     summary="Remove user from company",
     *     description="Removes a user's association with the company",
     *     security={{"apiAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of company user to remove",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User removed from company successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object"),
     *             @OA\Property(property="message", type="string", example="User removed from company successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Company user not found"
     *     )
     * )
     */
    public function destroy($id){
        return $this->repositoryObj->destroy($id);
    }
}
