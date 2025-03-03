<?php
/**
 * @OA\Info(
 *     title="User Management API",
 *     version="1.0.0",
 *     description="API endpoints for managing users"
 * )
 */

/**
 * @OA\Server(
 *     description="Laravel Swagger API Server",
 *     url=L5_SWAGGER_CONST_HOST
 * )
 */

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Repository\UserManagement\UserManagementInterface;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Users",
 *     description="API Endpoints for User Management"
 * )
 */
class UserController extends BaseController
{

    public $repositoryObj;

    public function __construct(UserManagementInterface $userObj)
    {
        $this->repositoryObj = $userObj;

    }


    /**
     * @OA\Get(
     *     path="/api/getAllUsers",
     *     tags={"Users"},
     *     summary="Get list of users",
     *     description="Returns list of users with pagination",
     *     security={{"apiAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array", @OA\Items(type="object")),
     *             @OA\Property(property="message", type="string", example="Users retrieved successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No users found"
     *     )
     * )
     */
    public function index(Request $request)
    {
        $response = $this->repositoryObj->index($request);
        if (!$response["success"]) {
            return $this->sendError('The Record Could not found.', ['error' => $response["message"]]);
        }
        return $this->sendResponse($response["data"], $response["message"]);
    }


    /**
     * @OA\Post(
     *     path="/api/addNewUser",
     *     tags={"Users"},
     *     summary="Create new user",
     *     description="Creates a new user and returns the user data",
     *     security={{"apiAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"username","fname","lname","email","password"},
     *             @OA\Property(property="username", type="string", example="johndoe"),
     *             @OA\Property(property="fname", type="string", example="John"),
     *             @OA\Property(property="lname", type="string", example="Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User created successfully"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $response = $this->repositoryObj->store($request);
        if (!$response["success"]) {
            return $this->sendError('The Record Could not Saved.', ['error' => $response["message"]]);
        }
        return $this->sendResponse($response["data"], $response["message"]);
    }


    /**
     * @OA\Get(
     *     path="/api/showUser/{id}",
     *     tags={"Users"},
     *     summary="Get user by ID",
     *     description="Returns user data",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of user to return",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found"
     *     )
     * )
     */
    public function show($id)
    {
        $response = $this->repositoryObj->show($id);
        if (!$response["success"]) {
            return $this->sendError('The Record Could not found.', ['error' => $response["message"]]);
        }
        return $this->sendResponse($response["data"], $response["message"]);
    }





    /**
     * @OA\Post(
     *     path="/api/updateUser/{id}",
     *     tags={"Users"},
     *     summary="Update user",
     *     description="Updates user data",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of user to update",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="username", type="string"),
     *             @OA\Property(property="fname", type="string"),
     *             @OA\Property(property="lname", type="string"),
     *             @OA\Property(property="email", type="string", format="email")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User updated successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $response = $this->repositoryObj->update($id, $request);
        if (!$response["success"]) {
            return $this->sendError('The Record Could not be updated.', ['error' => $response["message"]]);
        }
        return $this->sendResponse($response["data"], $response["message"]);
    }
    public function deactivateprofile($id,Request $request)
    {
        
        $response = $this->repositoryObj->deactivateprofile($id, $request);
        if (!$response["success"]) {
            return $this->sendError('The Record Could not be updated.', ['error' => $response["message"]]);
        }
        return $this->sendResponse($response["data"], $response["message"]);
    }
    


    /**
     * @OA\Delete(
     *     path="/api/deleteUser/{id}",
     *     tags={"Users"},
     *     summary="Delete user",
     *     description="Deletes a user by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of user to delete",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found"
     *     )
     * )
     */
    public function destroy($id)
    {
        $response = $this->repositoryObj->destroy($id);
        if (!$response["success"]) {
            return $this->sendError('The Record Could not be deleted.', ['error' => $response["message"]]);
        }
        return $this->sendResponse($response["data"], $response["message"]);
    }

    public function assignPermissionToRole($role, $permission)
    {
        $response = $this->repositoryObj->assignPermissionToRole($role, $permission);
        if (!$response["success"]) {
            return $this->sendError('permission Could not be assigned to role.', ['error' => $response["message"]]);
        }
        return $this->sendResponse($response["data"], $response["message"]);
    }



    public function removePermissionFromRole($role, $permission)
    {
        $response = $this->repositoryObj->removePermissionFromRole($role, $permission);
        if (!$response["success"]) {
            return $this->sendError('permission Could not be removed.', ['error' => $response["message"]]);
        }
        return $this->sendResponse($response["data"], $response["message"]);
    }



    /**
     * @OA\Post(
     *     path="/api/changepassword/{user}",
     *     tags={"Users"},
     *     summary="Change user password",
     *     description="Changes the password for a specific user",
     *     @OA\Parameter(
     *         name="user",
     *         in="path",
     *         description="ID of user",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"current_password", "new_password", "confirm_password"},
     *             @OA\Property(property="current_password", type="string", format="password"),
     *             @OA\Property(property="new_password", type="string", format="password"),
     *             @OA\Property(property="confirm_password", type="string", format="password")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Password changed successfully"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input"
     *     )
     * )
     */
    public function changePassword($id, Request $request)
    {
        $response = $this->repositoryObj->changePassword($id, $request);
        if (!$response["success"]) {
            return $this->sendError('password could not be changed.', ['error' => $response["message"]], $response["code"] );
        }
        return $this->sendResponse($response["data"], $response["message"]);
    }

    public function viewAssignPermissionToRole()
    {
        $response = $this->repositoryObj->viewAssignPermissionToRole();
        // if (!$response["success"]) {
        //     return $this->sendError('permission Could not be assigned to role.', ['error' => $response["message"]]);
        // }
        return $this->sendResponse($response["data"], $response["message"]);
    }

    /**
     * @OA\Post(
     *     path="/api/resetPasswordLink",
     *     tags={"Users"},
     *     summary="Send password reset link",
     *     description="Sends a password reset link to user's email",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email"},
     *             @OA\Property(property="email", type="string", format="email")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Reset link sent successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found"
     *     )
     * )
     */
    public function sendresetPasswordLink(Request $request){
        $response = $this->repositoryObj->resetPassword($request);
        if(!$response["success"]){
            return $this->sendError('Unable to Reset Password. Please check your credientials and try again!',['error' => $response["message"]]);
        }
        return $this->sendResponse($response["data"], $response["message"]);
    }

    /**
     * @OA\Post(
     *     path="/api/reset-password",
     *     tags={"Users"},
     *     summary="Reset password",
     *     description="Resets user password using token",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"token", "email", "password", "password_confirmation"},
     *             @OA\Property(property="token", type="string"),
     *             @OA\Property(property="email", type="string", format="email"),
     *             @OA\Property(property="password", type="string", format="password"),
     *             @OA\Property(property="password_confirmation", type="string", format="password")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Password reset successfully"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input"
     *     )
     * )
     */
    public function resetPassword(Request $request){
        $response = $this->repositoryObj->resetPassword($request);
        if(!$response["success"]){
            return $this->sendError('Unable to reset password.',['error' => $response["message"]]);
        }
        return $this->sendResponse($response["data"], $response["message"]);
    }

    /**
     * @OA\Get(
     *     path="/api/loggedInUser",
     *     tags={"Users"},
     *     summary="Get logged in user",
     *     description="Returns the currently authenticated user's data",
     *     security={{"apiAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="User data retrieved successfully"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */
    public function getSignedInUser(Request $request){
        $response = $this->repositoryObj->getSignedInUser($request);
        if(! $response["success"]){
            return $this->sendError('Unable to verify user.', ['error' => $response["message"]]);
        }
        return $this->sendResponse($response, 'User Found Successfully');
    }

}
