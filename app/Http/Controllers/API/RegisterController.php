<?php
namespace App\Http\Controllers\API;
use App\Repository\UserManagement\UserManagementInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Authentication",
 *     description="API Endpoints for User Registration and Authentication"
 * )
 */
class RegisterController extends BaseController
{
    public $userObj;
    public function __construct(UserManagementInterface $userObj)
    {
        $this->userObj = $userObj;
    }

    /**
     * @OA\Post(
     *     path="/api/register",
     *     tags={"Authentication"},
     *     summary="Register a new user",
     *     description="Creates a new user account and returns access token",
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
     *         response=200,
     *         description="User registered successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="accessToken", type="string"),
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="username", type="string"),
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="email", type="string"),
     *                 @OA\Property(property="image", type="string", nullable=true)
     *             ),
     *             @OA\Property(property="message", type="string", example="User registered successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="error code", type="string", example="401"),
     *             @OA\Property(property="error_message", type="array", @OA\Items(type="string"))
     *         )
     *     )
     * )
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username'=>'required|unique:users,username',
            'fname'=>'required',
            'lname'=>'required',
            'email'=>'required|email|unique:users,email',
            'password'=>'required',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->all();
            $response = [
                'success' => false,
                'error code' =>'401',
                'error_message' => $error,
            ];
            return $response;
        }

        $response = $this->userObj->store($request);
        $user = $response["data"];
        $success['accessToken'] =  $user->createToken('MyApp')->plainTextToken;
        $success['id'] =  $user->id;
        $success['username'] = $user->username;
        $success['name'] =   $user->fname . " " . $user->lname;
        $success['email'] = $user->email;
        $success['image'] = $user->image;
        if (!$response["success"]) {
            return $this->sendError('User not Registered.', ['error' => $response["message"]]);
        }
        return $this->sendResponse($success, $response["message"]);
    }

    /**
     * @OA\Post(
     *     path="/api/login",
     *     tags={"Authentication"},
     *     summary="Login user",
     *     description="Authenticates user and returns access token",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123"),
     *             @OA\Property(property="role", type="string", example="admin", description="Optional role to verify")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login successful",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="accessToken", type="string"),
     *                 @OA\Property(property="refreshToken", type="string"),
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="username", type="string"),
     *                 @OA\Property(property="fullName", type="string"),
     *                 @OA\Property(property="email", type="string"),
     *                 @OA\Property(property="avatar", type="string", nullable=true),
     *                 @OA\Property(property="role", type="array", @OA\Items(type="object")),
     *                 @OA\Property(property="ability", type="array", @OA\Items(type="object"))
     *             ),
     *             @OA\Property(property="message", type="string", example="User login successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Login failed",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error message", type="string", example="Unauthorised"),
     *             @OA\Property(property="error_code", type="string", example="404"),
     *             @OA\Property(property="success", type="boolean", example=false)
     *         )
     *     )
     * )
     */
    public function login(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            
            if($request->exists("role") && !($user->getRoleNames()->contains($request->role))){
                return [
                    "success"=> false,
                    "message" => "Access denied",
                ];
            }

            $getAbilitiesName = $user->getAbilitiesName();
            $token = $user->createToken('MyApp',$getAbilitiesName)->plainTextToken;
            $success['accessToken'] =  $token;
            $success['refreshToken'] =  $token;
            $success['id'] = $user->id;
            $success['username'] = $user->username;
            $success['fullName'] = $user->getFullName();
            $success['email'] = $user->email;
            $success['avatar'] = $user->image;
            $success['role'] = $user->roles;
            $permissions = $user->getAbility();
            $success['ability'] = $permissions;
            return $this->sendResponse($success, 'User login successfully.');
        } else {
            return response()->json(['error message'=>'Unauthorised','error_code'=>'404','success'=>false]);
        }
    }
}
