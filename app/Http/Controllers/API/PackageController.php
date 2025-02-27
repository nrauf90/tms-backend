<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repository\SaasModules\PackageManagement\PackageManagementInterface;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Packages",
 *     description="API Endpoints for Package Management"
 * )
 */
class PackageController extends BaseController
{
    public $repositoryObj;

    public function __construct(PackageManagementInterface $packageObj){
        $this->repositoryObj = $packageObj;
    }

    /**
     * @OA\Get(
     *     path="/api/packages",
     *     tags={"Packages"},
     *     summary="Get paginated list of packages",
     *     description="Returns paginated list of packages with their modules and features",
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
     *                     @OA\Property(property="name", type="string"),
     *                     @OA\Property(property="description", type="string"),
     *                     @OA\Property(property="price", type="number", format="float"),
     *                     @OA\Property(property="duration", type="integer"),
     *                     @OA\Property(property="status", type="boolean"),
     *                     @OA\Property(property="modules", type="array", @OA\Items(type="object")),
     *                     @OA\Property(property="created_at", type="string", format="date-time"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time")
     *                 )),
     *                 @OA\Property(property="total", type="integer"),
     *                 @OA\Property(property="per_page", type="integer")
     *             ),
     *             @OA\Property(property="message", type="string", example="Packages retrieved successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No packages found"
     *     )
     * )
     */
    public function index(Request $request){
        $response = $this->repositoryObj->index($request);
        if(! $response["success"]){
           return $this->sendError('No Package found', ['error' => $response["message"]]);
        }
        return $this->sendResponse($response["data"], $response["message"]);
    }

    /**
     * @OA\Post(
     *     path="/api/createPackages",
     *     tags={"Packages"},
     *     summary="Create new package",
     *     description="Creates a new package with specified modules and features",
     *     security={{"apiAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "description", "price", "duration"},
     *             @OA\Property(property="name", type="string", example="Enterprise Package"),
     *             @OA\Property(property="description", type="string", example="Complete enterprise solution"),
     *             @OA\Property(property="price", type="number", format="float", example=999.99),
     *             @OA\Property(property="duration", type="integer", example=30),
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="modules", type="array", @OA\Items(type="integer"), example={1,2,3})
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Package created successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object"),
     *             @OA\Property(property="message", type="string", example="Package created successfully")
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
            return $this->sendError('Unable to store package', ['error' => $response["message"]]);
        }
        return $this->sendResponse($response["data"], $response["message"]);
    }

    /**
     * @OA\Post(
     *     path="/api/updatePackages/{id}",
     *     tags={"Packages"},
     *     summary="Update package",
     *     description="Updates an existing package with new data",
     *     security={{"apiAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of package to update",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="description", type="string"),
     *             @OA\Property(property="price", type="number", format="float"),
     *             @OA\Property(property="duration", type="integer"),
     *             @OA\Property(property="status", type="boolean"),
     *             @OA\Property(property="modules", type="array", @OA\Items(type="integer"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Package updated successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object"),
     *             @OA\Property(property="message", type="string", example="Package updated successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Package not found"
     *     )
     * )
     */
    public function update($id, Request $request){
        $response = $this->repositoryObj->update($id, $request);
        if(!$response["success"]){
            return $this->sendError('Unable to update package', ['error' => $response["message"]]);
        }
        return $this->sendResponse($response["data"], $response["message"]);
    }

    /**
     * @OA\Delete(
     *     path="/api/deletePackages/{id}",
     *     tags={"Packages"},
     *     summary="Delete package",
     *     description="Deletes an existing package",
     *     security={{"apiAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of package to delete",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Package deleted successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object"),
     *             @OA\Property(property="message", type="string", example="Package deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Package not found"
     *     )
     * )
     */
    public function destroy($id){
        $response = $this->repositoryObj->destroy($id);
        if(!$response["success"]){
            return $this->sendError('Unable to delete package', ['error' => $response["message"]]);
        }
        return $this->sendResponse($response["data"], $response["message"]);
    }
    
    /**
     * @OA\Get(
     *     path="/api/packageDetails/{id}",
     *     tags={"Packages"},
     *     summary="Get package details",
     *     description="Returns detailed information about a specific package including its modules and features",
     *     security={{"apiAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of package to get details for",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="description", type="string"),
     *                 @OA\Property(property="price", type="number", format="float"),
     *                 @OA\Property(property="duration", type="integer"),
     *                 @OA\Property(property="status", type="boolean"),
     *                 @OA\Property(property="modules", type="array", @OA\Items(
     *                     @OA\Property(property="id", type="integer"),
     *                     @OA\Property(property="name", type="string"),
     *                     @OA\Property(property="features", type="array", @OA\Items(type="object"))
     *                 )),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             ),
     *             @OA\Property(property="message", type="string", example="Package details retrieved successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Package not found"
     *     )
     * )
     */
    public function details($id){
        $response = $this->repositoryObj->details($id);
        if(! $response["success"]){
            return $this->sendError('Unable to fetch details', ['error' => $response["message"]]);
        }
        return $this->sendResponse($response["data"], $response["message"]);
    }

    /**
     * @OA\Get(
     *     path="/api/all-packages",
     *     tags={"Packages"},
     *     summary="Get all packages",
     *     description="Returns list of all packages without pagination",
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
     *                 @OA\Property(property="price", type="number", format="float"),
     *                 @OA\Property(property="duration", type="integer"),
     *                 @OA\Property(property="status", type="boolean"),
     *                 @OA\Property(property="modules", type="array", @OA\Items(type="object")),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )),
     *             @OA\Property(property="message", type="string", example="All packages retrieved successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No packages found"
     *     )
     * )
     */
    public function allPackages(){
        $response = $this->repositoryObj->allPackages();
        if(! $response["success"]){
            return $this->sendError('No Package found', ['error' => $response["message"]]);
        }
        return $this->sendResponse($response["data"], $response["message"]);
    }
}
